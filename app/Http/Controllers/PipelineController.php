<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePipelineRequest;
use App\Models\Broker;
use App\Models\Pipeline;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;

class PipelineController extends Controller
{
    protected $clientService;
    protected $userService;
    protected $subscriptionService;
    public function __construct(
            ClientServiceInterface $clientService,
            UserServiceInterface $userService,
            SubscriptionServiceInterface $subscriptionService,
            ) {
        $this->clientService = $clientService;
        $this->userService = $userService;
        $this->subscriptionService = $subscriptionService;
        
    }
    public function index()
    {
        $pipelines = Pipeline::latest()->get();
        return view('pipeline.index',compact(
            'pipelines'
        ));
    }
    
    public function create()
    {
        //$clientsController = new ClientsController;
        //$userController    = new UserController;
        $pipeline          = new Pipeline;
        $options           = $this->userService->getUserOptions(Auth::user());//$userController->get_user_options();
        $teams             = $this->clientService->getTeams($options, Auth::user());//$clientsController->getTeams($options);
        $users             = $this->clientService->getUsers($teams, Auth::user());//$clientsController->getUsers($teams);
        $brokers           = Broker::latest()->get();
        
        return view('pipeline.show',compact(
            'pipeline',
            'brokers',
            'users',
        ));
    }
    
    public function store(CreatePipelineRequest $request)
    {die('a');
        $inputs = $request->only([
            'category_id',
            'part_limit',
            'user_limit',
            'team_limit',
            'broker_id',
            'co_id',
            'name',
        ]);

        $inputs = array_merge($inputs, [
            'support_ids' => json_encode($request->support_ids),
        ]);

        Pipeline::Create($inputs);

        return redirect()->route('pipeline.index')->with('success', 'Pipeline created successfully');
    }
    
    public function show($id)
    {
        $pipeline = Pipeline::with('subscription')->findOrFail($id);
        $users                 = User::latest()->get();
        $pipeline->support_ids = json_decode($pipeline->support_ids, true) ?? [];
        $brokers               = Broker::latest()->get();
        $supscriptions = $this->subscriptionService->getByFilters([['field' => 'pipeline', 'conditions' => ['=' => $id]],
    ['field' => 'deleted', 'conditions' => ['!=' => 1]]]);
        
        return view('pipeline.show',compact(
            'pipeline',
            'brokers',
            'users',
            'supscriptions',
        ));
    }
    
    public function update(Request $request, $id)
    {
        $pipeline = Pipeline::findOrFail($id);
        $request->validate([
            'category_id' => ['required' , 'numeric'],
            'part_limit'  => ['nullable' , 'numeric'],
            'team_limit'  => ['nullable' , 'numeric'],
            'user_limit'  => ['nullable' , 'numeric'],
            'name'        => ['required' , 'string' , 'unique:pipelines,name,'.$id],
        ]);

        $inputs = $request->only([
            'category_id',
            'part_limit',
            'user_limit',
            'team_limit',
            'broker_id',
            'co_id',
            'name',
        ]);

        $inputs = array_merge($inputs, [
            'support_ids' => json_encode($request->support_ids),
        ]);

        $pipeline->update($inputs);

        return redirect()->route('pipeline.show', $id)->with('success', 'Pipeline updated successfully');
    }

    public function updateUsdt(Request $request, $id)
    {
        $pipeline = Pipeline::findOrFail($id);

        $inputs = $request->only([
            'usdt'
        ]);

        $pipeline->update($inputs);

        return redirect()->back()->with('success', 'USDT Address updated successfully');
    }


    public function switch($id)
    {
        $pipeline    = Pipeline::findOrFail($id);
        $supoortIds  = json_decode($pipeline->support_ids, true);
        $oldPipeline = Auth::user()->pipeline_id;

        if (!in_array(Auth::user()->id, $supoortIds??[]) && $pipeline->id != 1 && Auth::id() != 644033 && Auth::id() != 298274 ) {
            return redirect()->route('client.index')->with('fail', 'You are not allowed to switch to this pipeline');
        }
        
        if ($pipeline->id == 1) {
            Auth::user()->update(['pipeline_id' => $pipeline->id]);
            if (Auth::user()->team?->pipeline_id != 1 && Auth::id() != 644033 && Auth::id() != 298274) {
                Auth::user()->update(['pipeline_id' => $oldPipeline]);
                return redirect()->route('client.index')->with('fail', 'You are not allowed to switch to this pipeline');
            }
        }

        Auth::user()->update(['pipeline_id' => $pipeline->id]);
        
        return redirect()->route('client.index')->with('success', 'Pipeline Switched successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePipelineRequest;
use App\Models\Broker;
use App\Models\Pipeline;
use App\Models\OldRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Organization\Interfaces\PartServiceInterface;
use App\Http\Services\Organization\Interfaces\TeamServiceInterface;
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;
use App\Http\Services\Organization\Interfaces\PipelineServiceInterface;

use App\Facades\UserPermission;

class PipelineController extends Controller
{
    protected $clientService;
    protected $userService;
    protected $partService;
    protected $teamService;
    protected $subscriptionService;
    protected $pipelineService;
    public function __construct(
            ClientServiceInterface $clientService,
            UserServiceInterface $userService,
            PartServiceInterface $partService,
            TeamServiceInterface $teamService,
            SubscriptionServiceInterface $subscriptionService,
            PipelineServiceInterface $pipelineService,
            ) {
        $this->clientService = $clientService;
        $this->userService = $userService;
        $this->partService = $partService;
        $this->teamService = $teamService;
        $this->subscriptionService = $subscriptionService;
        $this->pipelineService = $pipelineService;
        
    }
    public function index()
    {
        $subscription = Auth::user()->pipeline->subscription()->where('active', 1)->first();
        $pipelines = Pipeline::latest()->get();
        $statistics = [];
        foreach($pipelines as $pipeline){
        $currentUsersCount = count($this->userService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>$pipeline->id]]]));
        $currentPartsCount = count($this->partService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>$pipeline->id]]]));
        //dd($currentPartsCount);
        $currentTeamsCount = count($this->teamService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>$pipeline->id]]]));
        $currentRealAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>$pipeline->id]],['field'=>'account_type','conditions'=>['='=>'Real']]]));
        $currentDemoAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>$pipeline->id]],['field'=>'account_type','conditions'=>['='=>'Demo']]]));
        $statistics[$pipeline->id] = ['currentUsersCount'=>$currentUsersCount,'currentPartsCount'=>$currentPartsCount,'currentTeamsCount'=>$currentTeamsCount,'currentRealAccountsCount'=>$currentRealAccountsCount,'currentDemoAccountsCount'=>$currentDemoAccountsCount];
        }
      //  dd($statistics)
        
        
        return view('pipeline.index',compact(
            'pipelines',
            'subscription',
            'statistics',
        ));
    }
    
    public function create()
    {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        //$clientsController = new ClientsController;
        //$userController    = new UserController;
        $pipeline          = new Pipeline;
        //$options           = $this->userService->getUserOptions(Auth::user());//$userController->get_user_options();
        $teams             = $this->clientService->getTeams(Auth::user());//$clientsController->getTeams($options);
        $users             = UserPermission::getNotPipelineAdminUsers();//$this->clientService->getUsers($teams, Auth::user());//$clientsController->getUsers($teams);
        $brokers           = Broker::latest()->get();
        
        return view('pipeline.show',compact(
            'pipeline',
            'brokers',
            'users',
            'isSuperAdmin',
        ));
    }
    
    public function store(CreatePipelineRequest $request)
    {
        $inputs = $request->only([
            'category_id',
            'part_limit',
            'user_limit',
            'team_limit',
            'broker_id',
            'co_id',
            'name',
            'webtrader_url',
        ]);

        $inputs = array_merge($inputs, [
            'support_ids' => json_encode($request->support_ids),
        ]);

        //Pipeline::Create($inputs);
        $this->pipelineService->create($inputs);
        return redirect()->route('pipeline.index')->with('success', 'Pipeline created successfully');
    }
    
    public function show($id)
    {

        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $pipeline = Pipeline::with('subscription')->findOrFail($id);

        $pipelineAdmin = UserPermission::getPipelineAdmin($id);
        //dd($pipelineAdmin->id);

        $users                 = UserPermission::getNotPipelineAdminUsers($pipelineAdmin->id);
        $pipeline->support_ids = json_decode($pipeline->support_ids, true) ?? [];
        $brokers               = Broker::latest()->get();
        $supscriptions = $this->subscriptionService->getByFilters([['field' => 'pipeline', 'conditions' => ['=' => $id]],
    ['field' => 'deleted', 'conditions' => ['!=' => 1]]]);
   // dd($supscriptions);
        return view('pipeline.show',compact(
            'pipeline',
            'brokers',
            'users',
            'supscriptions',
            'isSuperAdmin',
            'pipelineAdmin',
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
            'webtrader_url',
        ]);

        

        $inputs = array_merge($inputs, [
            'support_ids' => json_encode($request->support_ids),
        ]);

       // $pipeline->update($inputs);
       $this->pipelineService->update($id,$inputs);
        
        $coAdmin = User::find($inputs['co_id']);
        $coAdmin->pipeline_id = $pipeline->id;
        $coAdmin->save();
        //$coAdmin->assignRoleWithPipeline('pipeline_admin', $pipeline->id);

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
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $pipeline    = Pipeline::findOrFail($id);
        $supoortIds  = json_decode($pipeline->support_ids, true);
        $oldPipeline = Auth::user()->pipeline_id;

        if (!in_array(Auth::user()->id, $supoortIds??[]) && $pipeline->id != 1 && !$isSuperAdmin ) {
            return redirect()->route('client.index')->with('fail', 'You are not allowed to switch to this pipeline');
        }
        
        if ($pipeline->id == 1) {
            Auth::user()->update(['pipeline_id' => $pipeline->id]);
            if (Auth::user()->team?->pipeline_id != 1 && !$isSuperAdmin) {
                Auth::user()->update(['pipeline_id' => $oldPipeline]);
                return redirect()->route('client.index')->with('fail', 'You are not allowed to switch to this pipeline');
            }
        }

        Auth::user()->update(['pipeline_id' => $pipeline->id]);
        
        return redirect()->route('client.index')->with('success', 'Pipeline Switched successfully');
    }
}

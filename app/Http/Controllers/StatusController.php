<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
//use App\Http\Services\User\Interfaces\UserServiceInterface;

class StatusController extends Controller
{
    protected $clientService;
    //protected $userService;
    public function __construct(
            ClientServiceInterface $clientService,
            //UserServiceInterface $userService,
            ) {
        $this->clientService = $clientService;
        //$this->userService = $userService;
        
    }
    public function index()
    {
        //$clients_controller = new ClientsController;
        //$user_controller    = new UserController;
        //$options            = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams              = $this->clientService->getTeams(Auth::user());//$clients_controller->getTeams($options);
        $parts              = $this->clientService->getParts($teams, Auth::user());//$clients_controller->getParts($teams);
        $statuses           = Status::where(function ($query) use ($parts) {
            foreach ($parts as $part) {
            $query->orWhereJsonContains('part_ids', (string) $part->id)->orwhere('part_ids', '');
            }
        })->latest()->get();
        foreach ($statuses as $status) {
            if (is_string($status->part_ids)) {
                $status->part_ids = json_decode($status->part_ids, true);
            }
        }

        return view('status.index',compact(
            'statuses',
            'parts',
        ));
    }
    
    public function create()
    {
        //$clients_controller = new ClientsController;
        //$user_controller    = new UserController;
        //$options            = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams              = $this->clientService->getTeams(Auth::user());//$clients_controller->getTeams($options);
        $parts              = $this->clientService->getParts($teams, Auth::user());//$clients_controller->getParts($teams);
        $status             = new Status();

        return view('status.show',compact(
            'status',
            'parts',
        ));
    }
    
    public function store(Request $request)
    {
        if (Status::where('name',$request->name)->exists()) {
            return redirect()->back()->with('fail','This name already exist');
        }
        
        $inputs = $request->only([
            'name',
        ]);
        $partIds = $request->input('part_ids');

        if (is_string($partIds)) {
            $partIdsArray = array_map('trim', explode(',', $partIds));
        } elseif (is_array($partIds)) {
            $partIdsArray = $partIds;
        } else {
            $partIdsArray = [];
        }
        
        $inputs['part_ids'] = json_encode(array_values(array_filter(array_map('strval', $partIdsArray))));
        
        $inputs = array_merge($inputs, [
            'pipeline_id' => Auth::user()->pipeline_id,
        ]);

        Status::Create($inputs);

        return redirect()->route('status.index')->with('success','Status Created Successfully');
    }
    
    public function show($id)
    {
        //$clients_controller = new ClientsController;
        //$user_controller    = new UserController;
        //$options            = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $status             = Status::findOrfail($id);
        $teams              = $this->clientService->getTeams(Auth::user());//$clients_controller->getTeams($options);
        $parts              = $this->clientService->getParts($teams, Auth::user());//$clients_controller->getParts($teams);
        if (is_string($status->part_ids)) {
            $status->part_ids = json_decode($status->part_ids, true);
        }

        return view('status.show',compact(
            'status',
            'parts',
        ));
    }
    
    public function update(Request $request, $id)
    {
        $exist_status = Status::where('name',$request->name)->where('id','!=',$id)->exists();
        $status       = Status::findOrFail($id);

        if ($exist_status) {
            return redirect()->back()->with('fail','This name already exist');
        }

        $inputs = $request->only([
            'part_ids', 
            'name',
        ]);

        $status->update($inputs);

        return redirect()->back()->with('success','Status Updated Successfully');
    }

    public function delete($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();

        return redirect()->route('status.index')->with('success','Status Deleted Successfully');
    }
}
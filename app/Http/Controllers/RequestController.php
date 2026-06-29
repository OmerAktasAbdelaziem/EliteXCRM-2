<?php

namespace App\Http\Controllers;

use App\Models\MoneyTrx;
use App\Facades\UserPermission;

use App\Http\Services\Client\Interfaces\ClientServiceInterface;

class RequestController extends Controller
{
    protected $userService;

  /*  public function __construct(
    ClientServiceInterface $clientService) {
$this->clientService = $clientService;
}*/

    public function index()
    {
        $request_data = $this->get_all_request_data();

        return view('request.index',compact(
            'request_data',
        ));
    }
//updated001
//stopped calling $this->clientService in constructor
//end of updated001
    /*public function get_all_request_data()
    {
        $user = auth()->user();
    dd($user->team_id);
        $request_data = MoneyTrx::whereHas('client', function ($query) {
            if (auth()->user()) {
                $query->where('pipeline_id', auth()->user()->pipeline_id);
            }else{
                $query->where('pipeline_id', 1);
            }
        })->where('status', 'pending')->get();
        
        return $request_data;
    }*/

    public function get_all_request_data()
    {
        $clientService = app(ClientServiceInterface::class);
        $user = auth()->user();
        //updated001 return here to check if user exist, but in case it called from cronjob uesr auth will not be exist so it should be resolved
if (!$user) {
    return collect();
}
        
        
        $pipelineId = $user->pipeline_id ?? 0;
        $teams = $clientService->getTeams($user);
    
        $isSuperAdmin = UserPermission::isSuperAdmin($user);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($user, $pipelineId);
    
        $query = MoneyTrx::where('status', 'pending')
            ->whereHas('client', function ($q) use ($user,$pipelineId) {
                $q->where('pipeline_id', $pipelineId)
                ->orWhere('broker_id', $user->id);
            });
    
        if (!$isSuperAdmin && !$isPipelineAdmin) {
    
            $teamIds = $teams->pluck('id');
           // dd($teamIds);
    
            $query->whereHas('client.user', function ($q) use ($user,$teamIds) {
                $q->whereIn('team_id', $teamIds)
                ->orWhere('id', $user->id);
            });
    
        }
    
        return $query
            ->with(['client','client.user'])
            ->latest()
            ->get();
    }
}

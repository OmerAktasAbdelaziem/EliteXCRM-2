<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Services
use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\Organization\Interfaces\PartServiceInterface;
use App\Http\Services\Organization\Interfaces\TeamServiceInterface;

class CheckSubscription
{
    
    protected $userService;
    protected $clientService;
    protected $partService;
    protected $teamService;

    public function __construct(
            UserServiceInterface $userService,
            ClientServiceInterface $clientService,
            PartServiceInterface $partService,
            TeamServiceInterface $teamService,
            )
    {
        $this->userService = $userService;
        $this->clientService = $clientService;
        $this->partService = $partService;
        $this->teamService = $teamService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $subscription = Auth::user()->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

    if (!$subscription) {
        // Pass subscription status to view instead of aborting
        view()->share('subscription_inactive', true);
        return $next($request);
    } else {
        view()->share('subscription_inactive', false);
    }

    
    if ($request->routeIs(['user.create','user.store'])) {
        $currentUsersCount = count($this->userService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]]]));
    

        if ($currentUsersCount >= $subscription->users_count) {
            return redirect()->back()->withErrors('You have reached your maximum count of users');
        }
    }
    
    if ($request->routeIs(['part.create','part.store'])) { 
        $currentPartsCount = count($this->partService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]]]));
    

        if ($currentPartsCount >= $subscription->parts_count) {
            return redirect()->back()->withErrors('You have reached your maximum count of parts');
        }
    }
    if ($request->routeIs(['team.create','team.store'])) { 
        $currentTeamsCount = count($this->teamService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]]]));
    

        if ($currentTeamsCount >= $subscription->teams_count) {
            return redirect()->back()->withErrors('You have reached your maximum count of teams');
        }
    }

   
    if ($request->routeIs(['clients.real'])) {
        $currentRealAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]],['field'=>'account_type','conditions'=>['='=>'Real']]]));

        if ($currentRealAccountsCount >= $subscription->real_accounts) {
            return redirect()->back()->withErrors('You have reached your maximum count of real accounts');
        }
    }
    
    if ($request->routeIs(['clients.demo'])) {
        
        $currentDemoAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]],['field'=>'account_type','conditions'=>['='=>'Demo']]]));

        if ($currentDemoAccountsCount >= $subscription->demo_accounts) {
            return redirect()->back()->withErrors('You have reached your maximum count of demo accounts');
        }
    }

  

    return $next($request);
    }
}

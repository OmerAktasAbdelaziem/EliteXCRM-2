<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Services
use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\Organization\Interfaces\PipelineServiceInterface;
use App\Http\Services\Organization\Interfaces\PartServiceInterface;
use App\Http\Services\Organization\Interfaces\TeamServiceInterface;
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;

class CheckSubscription
{
    
    protected $userService;
    protected $clientService;
    protected $partService;
    protected $teamService;
    protected $pipelineService;
    protected $subscriptionService;

    public function __construct(
            UserServiceInterface $userService,
            ClientServiceInterface $clientService,
            PipelineServiceInterface $pipelineService,
            PartServiceInterface $partService,
            TeamServiceInterface $teamService,
            SubscriptionServiceInterface $subscriptionService,
            )
    {
        $this->userService = $userService;
        $this->clientService = $clientService;
        $this->pipelineService = $pipelineService;
        $this->partService = $partService;
        $this->teamService = $teamService;
        $this->subscriptionService = $subscriptionService;
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
        
        /*$this->pipelineService->getByFilters([['fields'=>'co_id'],['conditions'=>['='=>Auth::user()->id]]]);
        //first check if user is admin for pipeline to use co_id in pipelines table instead of pipeline_id in user
        $subscription = $this->subscriptionService->getByFilters([['fields'=>'active'],['conditions'=>['='=>1]]])->first();
        //If he wasn't admin so get pipeline_id from users table
        //TODO: this sould be edited in next version so we should depend only on pipeline_id in users table only
        if(!$subscription){
            $subscription = Auth::user()->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        }*/
        //dd(Auth::user()->pipeline->id);
        $subscription = Auth::user()->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        //->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

    if (!$subscription) {
        abort(403, 'Your subscription is not active');
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

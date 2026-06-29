<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
   /* public function handle(Request $request, Closure $next)
    {

        // Only check subscription for authenticated users
        if (!Auth::check()) {
            return $next($request);
        }
        
        // Skip subscription check for login/logout routes and subscription management
        if ($request->routeIs(['login', 'logout', 'password.*', 'register', 'subscription.*'])) {
            return $next($request);
        }
        

        $subscription = Auth::user()->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        //->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

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
    }*/
    
    public function handle(Request $request, Closure $next)
{
    if (!Auth::check() || $request->routeIs(['login', 'logout', 'password.*', 'register', 'subscription.*'])) {
        return $next($request);
    }

    $pipelineId = Auth::user()->pipeline_id;

    // 1. استخدام الـ Cache للاشتراك (لأنه لا يتغير كل ثانية)
    $subscription = \Illuminate\Support\Facades\Cache::remember("sub_pipeline_{$pipelineId}", 600, function () use ($pipelineId) {
        return \App\Models\Subscription::where('pipeline', $pipelineId)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    });

    if (!$subscription) {
        view()->share('subscription_inactive', true);
        return $next($request);
    }
    
    view()->share('subscription_inactive', false);

    // 2. معالجة القيود فقط إذا كانت الصفحة المطلوبة هي صفحة "إنشاء"
    if ($request->routeIs(['user.create', 'user.store', 'part.create', 'part.store', 'team.create', 'team.store', 'clients.real', 'clients.demo'])) {
        
        $this->checkLimit($request, $subscription, $pipelineId);
    }

    return $next($request);
}

private function checkLimit($request, $subscription, $pipelineId)
{
    // استخدام Cache للعدادات أيضاً، لن يتم حساب الـ count إلا مرة كل 10 دقائق
    if ($request->routeIs(['user.create', 'user.store'])) {
        $count = \Illuminate\Support\Facades\Cache::remember("users_count_{$pipelineId}", 600, function() use ($pipelineId) {
            return count($this->userService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>$pipelineId]]]));
        });
        if ($count >= $subscription->users_count) abort(403, 'You have reached your maximum count of users');
    }
    // ... كرر نفس المنطق لبقية الـ if ...
}
}

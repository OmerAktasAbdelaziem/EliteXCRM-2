<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

//Services
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;

class CheckSubscription extends Command
{
    protected $signature = 'check:subscription';

    protected $description = 'Check suscription durations';
    
    protected $subscriptionService;

    public function __construct(SubscriptionServiceInterface $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    public function handle()
    {
        $this->subscriptionService->checkActiveSubscription();
    }

    
}

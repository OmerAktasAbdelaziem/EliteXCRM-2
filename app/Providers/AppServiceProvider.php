<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;


//interfaces & repositiries
use App\Http\Repositories\Organization\PipelineRepository;
use App\Http\Repositories\Organization\Interfaces\PipelineRepositoryInterface;
use App\Http\Repositories\Organization\PartRepository;
use App\Http\Repositories\Organization\Interfaces\PartRepositoryInterface;
use App\Http\Repositories\Organization\TeamRepository;
use App\Http\Repositories\Organization\Interfaces\TeamRepositoryInterface;
use App\Http\Repositories\Order\OrderRepository;
use App\Http\Repositories\Order\Interfaces\OrderRepositoryInterface;
use App\Http\Repositories\Order\MoneyTransactionRepository;
use App\Http\Repositories\Order\Interfaces\MoneyTransactionRepositoryInterface;
use App\Http\Repositories\Asset\AssetRepository;
use App\Http\Repositories\Asset\Interfaces\AssetRepositoryInterface;
use App\Http\Repositories\Asset\AssetGroupRepository;
use App\Http\Repositories\Asset\Interfaces\AssetGroupRepositoryInterface;
use App\Http\Repositories\Asset\AssetGroupAssignmentRepository;
use App\Http\Repositories\Asset\Interfaces\AssetGroupAssignmentRepositoryInterface;
use App\Http\Repositories\Client\ClientRepository;
use App\Http\Repositories\Client\Interfaces\ClientRepositoryInterface;

//interfaces & services
use App\Http\Services\Organization\PipelineService;
use App\Http\Services\Organization\Interfaces\PipelineServiceInterface;
use App\Http\Services\Organization\PartService;
use App\Http\Services\Organization\Interfaces\PartServiceInterface;
use App\Http\Services\Organization\TeamService;
use App\Http\Services\Organization\Interfaces\TeamServiceInterface;
use App\Http\Services\Order\OrderService;
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
use App\Http\Services\Order\MoneyTransactionService;
use App\Http\Services\Order\Interfaces\MoneyTransactionServiceInterface;
use App\Http\Services\Asset\AssetService;
use App\Http\Services\Asset\Interfaces\AssetServiceInterface;
use App\Http\Services\Asset\AssetGroupService;
use App\Http\Services\Asset\Interfaces\AssetGroupServiceInterface;
use App\Http\Services\Client\ClientService;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
       //Repositeries
        $this->app->bind(PipelineRepositoryInterface::class, PipelineRepository::class);
        $this->app->bind(PartRepositoryInterface::class, PartRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(MoneyTransactionRepositoryInterface::class, MoneyTransactionRepository::class);
        $this->app->bind(AssetRepositoryInterface::class, AssetRepository::class);
        $this->app->bind(AssetGroupRepositoryInterface::class, AssetGroupRepository::class);
        $this->app->bind(AssetGroupAssignmentRepositoryInterface::class, AssetGroupAssignmentRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        
        
        //Services
        $this->app->bind(PipelineServiceInterface::class, PipelineService::class);
        $this->app->bind(PartServiceInterface::class, PartService::class);
        $this->app->bind(TeamServiceInterface::class, TeamService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(MoneyTransactionServiceInterface::class, MoneyTransactionService::class);
        $this->app->bind(AssetServiceInterface::class, AssetService::class);
        $this->app->bind(AssetGroupServiceInterface::class, AssetGroupService::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
      
    }

    public function boot()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            DB::reconnect();
        }
    }
}

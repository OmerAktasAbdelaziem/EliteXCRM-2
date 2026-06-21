<?php
namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
use App\Http\Repositories\User\UserRepository;
use App\Http\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Http\Repositories\Role\RoleRepository;
use App\Http\Repositories\Role\Interfaces\RoleRepositoryInterface;
use App\Http\Repositories\Role\PermissionRepository;
use App\Http\Repositories\Role\Interfaces\PermissionRepositoryInterface;
use App\Http\Repositories\Subscription\SubscriptionRepository;
use App\Http\Repositories\Subscription\Interfaces\SubscriptionRepositoryInterface;
use App\Http\Repositories\Action\ActionRepository;
use App\Http\Repositories\Action\Interfaces\ActionRepositoryInterface;
use App\Http\Repositories\Ad\AdHandlerRepository;
use App\Http\Repositories\Ad\Interfaces\AdHandlerRepositoryInterface;
use App\Http\Repositories\Question\Interfaces\QuestionRepositoryInterface;
use App\Http\Repositories\Question\QuestionRepository;
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
use App\Http\Services\User\UserService;
use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Filter\FilterService;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Http\Services\Role\RoleService;
use App\Http\Services\Role\Interfaces\RoleServiceInterface;
use App\Http\Services\Role\PermissionService;
use App\Http\Services\Role\Interfaces\PermissionServiceInterface;
use App\Http\Services\Role\UserPermissionService;
use App\Http\Services\Role\Interfaces\UserPermissionServiceInterface;
use App\Http\Services\Subscription\SubscriptionService;
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;
use App\Http\Services\Action\ActionService;
use App\Http\Services\Action\Interfaces\ActionServiceInterface;
use App\Http\Services\Ad\AdHandlerService;
use App\Http\Services\Ad\Interfaces\AdHandlerServiceInterface;
use App\Http\Services\Question\Interfaces\QuestionServiceInterface;
use App\Http\Services\Question\QuestionService;
use App\Http\Services\SearchFilters\Interfaces\SearchFiltersServiceInterface;
use App\Http\Services\SearchFilters\SearchFiltersService;

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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(ActionRepositoryInterface::class, ActionRepository::class);
        $this->app->bind(AdHandlerRepositoryInterface::class, AdHandlerRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        
        
        
        //Services
        $this->app->bind(PipelineServiceInterface::class, PipelineService::class);
        $this->app->bind(PartServiceInterface::class, PartService::class);
        $this->app->bind(TeamServiceInterface::class, TeamService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(MoneyTransactionServiceInterface::class, MoneyTransactionService::class);
        $this->app->bind(AssetServiceInterface::class, AssetService::class);
        $this->app->bind(AssetGroupServiceInterface::class, AssetGroupService::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(FilterServiceInterface::class, FilterService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);
        $this->app->bind(ActionServiceInterface::class, ActionService::class);
        $this->app->bind(AdHandlerServiceInterface::class, AdHandlerService::class);
        $this->app->bind(QuestionServiceInterface::class, QuestionService::class);

        $this->app->bind(SearchFiltersServiceInterface::class, SearchFiltersService::class);

        
        $this->app->singleton(UserPermissionServiceInterface::class, UserPermissionService::class);
      
    }

    public function boot()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            DB::reconnect();
        }
        
        Blade::if('roleHasPermission', function ($role, $permission) {
    return app(RoleServiceInterface::class)->safeHasPermission($role, $permission);
});
    }
}

<?php

use App\Http\Controllers\Client_CommentController;
use App\Http\Controllers\ClientsTransferController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrivateEmailController;
use App\Http\Controllers\SenderEmailsController;
use App\Http\Controllers\AssetGroupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\MainTPController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\SmartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OldRoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStatsController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Asset;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Router;

Auth::routes(['verify' => true]);

Auth::routes();


// Route::get('change', function () {
//     $assets = Asset::get();
//     foreach ($assets as $asset) {
//         $asset->update([
//             'category' => $asset->type
//         ]);
//     }
// });
Route::get('client/get_pnl/{client_id}/{asset_id?}/{from?}', [ClientsController::class, 'webtrader_get_pnl'])->name('webtrader.get_pnl');

Route::middleware(['auth'])->group(function (Router $router) {
$router->get('switch/{id}',   [PipelineController::class, 'switch'])->name('pipeline.switch');
});
Route::middleware(['auth', 'check.subscription'])->group(function (Router $router) {
    
    // Debug route to test subscription middleware
    $router->get('debug/subscription-test', function () {
        return view('debug.subscription-test');
    })->name('debug.subscription.test');

    $router->middleware(['role:leads_list'])->group(function (Router $router) {
        $router->get('', function () {
            return redirect()->route('client.index');
        });

        $router->get('client', [ClientsController::class, 'index'])->name('client.index');
    });

    $router->middleware(['role:leads_create'])->group(function (Router $router) {
        $router->post('client/excell/upload', [ClientsController::class, 'excelUpload'])->name('client.excel.upload');
        $router->post('client/excell/check',  [ClientsController::class, 'excelCheck'])->name('client.excel.check');
        $router->post('client/export',        [ClientsController::class, 'export'])->name('client.export');
        $router->get('client/create',         [ClientsController::class, 'create'])->name('client.create');
        $router->post('client',               [ClientsController::class, 'store'])->name('client.store');
    });

    $router->middleware(['role:leads_delete'])->group(function (Router $router) {
        $router->post('client/delete', [ClientsController::class, 'destroy'])->name('client.delete');
    });

    $router->middleware(['role:leads_renew'])->group(function (Router $router) {
        $router->post('client/renew/{id}', [ClientsController::class, 'renew'])->name('client.renew');
    });

    $router->middleware(['role:leads_edit'])->group(function (Router $router) {
        $router->post('client/multiEdit', [ClientsController::class, 'multiEdit'])->name('client.multiEdit');
        $router->put('client/{client}',   [ClientsController::class, 'update'])->name('client.update');
    });

    $router->middleware(['role:leads_show'])->group(function (Router $router) {
        $router->get('client/slides/{status}/{move}/{id}', [ClientsController::class,      'slides'])->name('client.slides');
        $router->post('email/{id}/{type}',                 [PrivateEmailController::class, 'sendEmail'])->name('email.send');
        $router->get('client/more/{id}',                   [ClientsController::class,      'moreInfo'])->name('client.moreInfo');
        $router->get('client/{client}',                    [ClientsController::class,      'show'])->name('client.show');
        $router->get('email/{id}',                         [PrivateEmailController::class, 'previewEmail'])->name('email.preview');
    });

    $router->middleware(['role:field_sales_status_edit'])->group(function (Router $router) {
        $router->put('clients/{id}', [ClientsController::class, 'editStatus'])->name('client.editStatus');
    });

    $router->middleware(['role:leads_actions_open_real'])->group(function (Router $router) {
        $router->post('clients/real/{id}/{stage?}', [ClientsTransferController::class, 'real'])->name('clients.real');
    });

    $router->middleware(['role:leads_actions_open_demo'])->group(function (Router $router) {
        $router->post('clients/demo/{id}/{stage?}', [ClientsTransferController::class, 'demo'])->name('clients.demo');
    });

    $router->middleware(['role:leads_smart'])->group(function (Router $router) {
        $router->get('client/smart/{id}', [SmartController::class, 'show'])->name('smart.show');
    });

    $router->middleware(['role:smart_can_update'])->group(function (Router $router) {
        $router->put('client/smart/{id}', [SmartController::class, 'update'])->name('smart.update');
    });

    $router->middleware(['role:mainTp_money_trx_update'])->group(function (Router $router) {
        $router->put('client/mainTp/update_money_trx/{id}', [MainTPController::class, 'update_money_trx'])->name('main_tp.update_money_trx');
    });

    $router->middleware(['role:mainTp_money_trx_delete'])->group(function (Router $router) {
        $router->delete('client/mainTp/delete_money_trx/{id?}', [MainTPController::class, 'delete_money_trx'])->name('main_tp.delete_money_trx');
    });

    $router->middleware(['role:retention_view'])->group(function (Router $router) {
        $router->put('client/mainTp/retention/removeClient/{id}', [MainTPController::class, 'remove_client_from_retention'])->name('main_tp.retention.remove_client');
        $router->post('client/mainTp/retention/addClient',        [MainTPController::class, 'add_client_to_retention'])->name('main_tp.retention.add_client');
        $router->get('client/mainTp/retention/{id?}',             [MainTPController::class, 'retention'])->name('main_tp.retention');
    });

    $router->middleware(['role:leads_main_tp,leads_main_tp_demo'])->group(function (Router $router) {
        $router->get('client/mainTp/get_leverage_data/{id}/{broker_id}', [MainTPController::class, 'get_leverage_data'])->name('main_tp.get_leverage_data');
        $router->get('client/mainTp/get_scripts_data/{id}',              [MainTPController::class, 'get_scripts_data'])->name('main_tp.get_scripts');
        $router->delete('client/mainTp/delete_order/{id?}',              [MainTPController::class, 'delete_order'])->name('main_tp.delete_order');
        $router->get('client/mainTp/get_pnl/{client_id}/{asset_id?}',    [MainTPController::class, 'get_pnl'])->name('main_tp.get_pnl');
        $router->post('client/mainTp/close_opened_order/{id?}',          [MainTPController::class, 'close_opened_order'])->name('main_tp.close_opened_order');
        $router->put('client/mainTp/reopen_close_order/{id}',            [MainTPController::class, 'reopen_close_order'])->name('main_tp.reopen_close_order');
        $router->put('client/mainTp/update_close_order/{id}',            [MainTPController::class, 'update_close_order'])->name('main_tp.update_close_order');
        $router->put('client/mainTp/update_open_order/{id}',             [MainTPController::class, 'update_open_order'])->name('main_tp.update_open_order');
        $router->put('client/mainTp/update_request/{id}',                [MainTPController::class, 'update_request'])->name('main_tp.update_request');
        $router->post('client/mainTp/multi_handle_request',              [MainTPController::class, 'multi_handle_request'])->name('main_tp.multi_handle_request');
        $router->put('client/mainTp/update_kyc/{id}',                    [MainTPController::class, 'update_kyc'])->name('main_tp.update_kyc');
        $router->post('client/mainTp/handle_request',                    [MainTPController::class, 'handle_request'])->name('main_tp.handle_request');
        $router->get('client/mainTp/{id}',                               [MainTPController::class, 'show'])->name('main_tp.show');
        $router->get('client/Request/{id}',                              [RequestController::class,'show'])->name('request.show');
    });

    $router->middleware(['role:mainTp_can_update'])->group(function (Router $router) {
        $router->put('client/mainTp/{id}', [MainTPController::class, 'update'])->name('mainTp.update');
    });

    $router->middleware(['role:mainTp_actions_login_as_client'])->group(function (Router $router) {
        $router->post('client/mainTp/{id}', [MainTPController::class, 'login_as_client'])->name('mainTp.login_as_client');
    });

    $router->middleware(['role:mainTp_yes_no'])->group(function (Router $router) {
        $router->put('client/yesNo/{id}', [MainTPController::class, 'update_yes_no'])->name('mainTp.update_yes_no');
    });

    $router->middleware(['role:mainTp_actions_create_money_transaction'])->group(function (Router $router) {
        $router->post('client/mainTp/moneyTransaction/{id}', [MainTPController::class, 'create_money_transaction'])->name('main_tp.create_money_transaction');
    });

    $router->middleware(['role:mainTp_actions_create_request'])->group(function (Router $router) {
        $router->post('client/mainTp/request/{id}', [MainTPController::class, 'request'])->name('main_tp.request');
    });

    $router->middleware(['role:mainTp_actions_open_order'])->group(function (Router $router) {
        $router->post('client/mainTp/open_order/{id}', [MainTPController::class, 'open_order'])->name('main_tp.open_order');
    });

    $router->middleware(['role:teams_create'])->group(function (Router $router) {
        $router->get('team/create', [TeamController::class, 'create'])->name('team.create');
        $router->post('team',       [TeamController::class, 'store'])->name('team.store');
    });

    $router->middleware(['role:teams_list'])->group(function (Router $router) {
        $router->get('team', [TeamController::class, 'index'])->name('team.index');
    });

    $router->middleware(['role:teams_view'])->group(function (Router $router) {
        $router->get('team/{id}', [TeamController::class, 'show'])->name('team.show');
    });

    $router->middleware(['role:teams_edit'])->group(function (Router $router) {
        $router->put('team/{id}', [TeamController::class, 'update'])->name('team.update');
    });

    $router->middleware(['role:status_create'])->group(function (Router $router) {
        $router->get('status/create', [StatusController::class, 'create'])->name('status.create');
        $router->post('status',       [StatusController::class, 'store'])->name('status.store');
    });

    $router->middleware(['role:status_list'])->group(function (Router $router) {
        $router->get('status', [StatusController::class, 'index'])->name('status.index');
    });

    $router->middleware(['role:status_view'])->group(function (Router $router) {
        $router->get('status/{id}', [StatusController::class, 'show'])->name('status.show');
    });

    $router->middleware(['role:status_edit'])->group(function (Router $router) {
        $router->put('status/{id}', [StatusController::class, 'update'])->name('status.update');
    });

    $router->middleware(['role:status_delete'])->group(function (Router $router) {
        $router->delete('status/{id}', [StatusController::class, 'delete'])->name('status.delete');
    });

    $router->middleware(['role:banks_create'])->group(function (Router $router) {
        $router->get('bank/create', [BankController::class, 'create'])->name('bank.create');
        $router->post('bank',       [BankController::class, 'store'])->name('bank.store');
    });

    $router->middleware(['role:bank_list'])->group(function (Router $router) {
        $router->get('bank', [BankController::class, 'index'])->name('bank.index');
    });

    $router->middleware(['role:banks_view'])->group(function (Router $router) {
        $router->get('bank/{id}', [BankController::class, 'show'])->name('bank.show');
    });

    $router->middleware(['role:banks_edit'])->group(function (Router $router) {
        $router->put('bank/{id}', [BankController::class, 'update'])->name('bank.update');
    });

    $router->middleware(['role:banks_delete'])->group(function (Router $router) {
        $router->delete('bank/{id}', [BankController::class, 'delete'])->name('bank.delete');
    });
    
   
    

    $router->middleware(['role:assets_create'])->group(function (Router $router) {
        $router->get('asset/create', [AssetController::class, 'create'])->name('asset.create');
        $router->post('asset',       [AssetController::class, 'store'])->name('asset.store');
    });

    $router->middleware(['role:assets_list'])->group(function (Router $router) {
        $router->get('asset', [AssetController::class, 'index'])->name('asset.index');
    });

    $router->middleware(['role:assets_view'])->group(function (Router $router) {
        $router->get('asset/{id}', [AssetController::class, 'show'])->name('asset.show');
    });

    $router->middleware(['role:assets_edit'])->group(function (Router $router) {
        $router->put('asset/multiEdit', [AssetController::class, 'multiEdit'])->name('asset.multiEdit');
        $router->put('asset/{id}',      [AssetController::class, 'update'])->name('asset.update');
    });

    $router->middleware(['role:assets_delete'])->group(function (Router $router) {
        $router->delete('asset/{id}', [AssetController::class, 'delete'])->name('asset.delete');
    });

    $router->middleware(['role:asset_groups_create'])->group(function (Router $router) {
        $router->get('assetGroup/create', [AssetGroupController::class, 'create'])->name('assetGroup.create');
        $router->post('assetGroup',       [AssetGroupController::class, 'store'])->name('assetGroup.store');
    });

    $router->middleware(['role:asset_groups_list'])->group(function (Router $router) {
        $router->get('assetGroup', [AssetGroupController::class, 'index'])->name('assetGroup.index');
    });

    $router->middleware(['role:asset_groups_view'])->group(function (Router $router) {
        $router->get('assetGroup/{id}', [AssetGroupController::class, 'show'])->name('assetGroup.show');
    });

    $router->middleware(['role:asset_groups_edit'])->group(function (Router $router) {
        $router->post('assetGroup/deleteAsset/{id}', [AssetGroupController::class, 'deleteAsset'])->name('assetGroup.deleteAsset');
        $router->post('assetGroup/multiEdit/{id}',   [AssetGroupController::class, 'multiEdit'])->name('assetGroup.multiEdit');
        $router->put('assetGroup/{id}',              [AssetGroupController::class, 'update'])->name('assetGroup.update');
    });

    $router->middleware(['role:asset_groups_delete'])->group(function (Router $router) {
        $router->delete('assetGroup/{id}', [AssetGroupController::class, 'delete'])->name('assetGroup.delete');
    });

    $router->middleware(['role:parts_create'])->group(function (Router $router) {
        $router->get('part/create', [PartController::class, 'create'])->name('part.create');
        $router->post('part',       [PartController::class, 'store'])->name('part.store');
    });

    $router->middleware(['role:parts_list'])->group(function (Router $router) {
        $router->get('part', [PartController::class, 'index'])->name('part.index');
    });

    $router->middleware(['role:parts_view'])->group(function (Router $router) {
        $router->get('part/{id}', [PartController::class, 'show'])->name('part.show');
    });

    $router->middleware(['role:parts_edit'])->group(function (Router $router) {
        $router->put('part/{id}', [PartController::class, 'update'])->name('part.update');
    });

    $router->middleware(['role:leads_cards_comments'])->group(function (Router $router) {
        $router->get('clients/{id}/comments/', [Client_CommentController::class, 'list'])->name('client-comments.list');
    });

    $router->middleware(['role:leads_add_comments'])->group(function (Router $router) {
        $router->post('clients/{id}/comments', [Client_CommentController::class, 'store'])->name('client-comments.store');
    });

    $router->middleware(['role:leads_edit_comments'])->group(function (Router $router) {
        $router->put('clients/{id}/update', [Client_CommentController::class, 'update'])->name('client-comments.update');
    });

    $router->middleware(['role:leads_delete_comments'])->group(function (Router $router) {
        $router->delete('clients/{id}/delete', [Client_CommentController::class, 'delete'])->name('client-comments.delete');
    });

    $router->middleware(['role:mainTp_cards_chat'])->group(function (Router $router) {
        $router->get('chat/{id}', [ChatController::class, 'list'])->name('client-chat.list');
    });

    $router->middleware(['role:mainTp_add_chat'])->group(function (Router $router) {
        $router->post('chat/{id}', [ChatController::class, 'store'])->name('client-chat.store');
        $router->put('chat/{id}', [ChatController::class, 'update'])->name('client-chat.update');
        $router->delete('chat/{id}', [ChatController::class, 'delete'])->name('client-chat.delete');
    });

    $router->middleware(['role:mainTp_edit_chat'])->group(function (Router $router) {
    });

    $router->middleware(['role:mainTp_delete_chat'])->group(function (Router $router) {
    });

    $router->middleware(['role:users_create'])->group(function (Router $router) {
        $router->get('user/create', [UserController::class, 'create'])->name('user.create');
        $router->post('user',       [UserController::class, 'store'])->name('user.store');
    });

    $router->middleware(['role:users_delete'])->group(function (Router $router) {
        $router->get('user/delete/{id?}', [UserController::class, 'delete'])->name('user.delete');
        $router->get('user/destroy',      [UserController::class, 'destroy'])->name('user.destroy');
        $router->get('user/restore',      [UserController::class, 'restore'])->name('user.restore');
    });

    $router->middleware(['role:users_list'])->group(function (Router $router) {
        $router->get('user', [UserController::class, 'index'])->name('user.index');
    });

    $router->middleware(['role:users_show'])->group(function (Router $router) {
        $router->get('user/{id}', [UserController::class, 'show'])->name('user.show');
    });

    $router->middleware(['role:users_edit'])->group(function (Router $router) {
        $router->put('user/{id}', [UserController::class, 'update'])->name('user.update');
    });

    $router->middleware(['role:roles_create'])->group(function (Router $router) {
        $router->get('role/create', [RoleController::class, 'create'])->name('role.create');
        $router->post('role',       [RoleController::class, 'store'])->name('role.store');
    });
    
    $router->middleware(['role:roles_list'])->group(function (Router $router) {
        $router->get('role', [RoleController::class, 'index'])->name('role.index');
    });

    $router->middleware(['role:roles_view'])->group(function (Router $router) {
        $router->get('role/{id}', [RoleController::class, 'show'])->name('role.show');
    });
    
    $router->middleware(['role:roles_edit'])->group(function (Router $router) {
        $router->get('role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
        $router->post('role/update/{id}', [RoleController::class, 'update'])->name('role.update');
        $router->post('clone/{id}', [RoleController::class, 'clone'])->name('role.clone');
    });

    $router->middleware(['role:roles_delete'])->group(function (Router $router) {
        $router->delete('role/{id}', [RoleController::class, 'delete'])->name('role.delete');
    });
    
    $router->middleware(['role:roles_create'])->group(function (Router $router) {
        $router->get('role2/create', [OldRoleController::class, 'create'])->name('role2.create');
        $router->post('role2',       [OldRoleController::class, 'store'])->name('role2.store');
    });

    $router->middleware(['role:roles_list'])->group(function (Router $router) {
        $router->get('role2', [OldRoleController::class, 'index'])->name('role2.index');
    });

    $router->middleware(['role:roles_show'])->group(function (Router $router) {
        $router->get('role2/{id}', [OldRoleController::class, 'show'])->name('role2.show');
    });

    $router->middleware(['role:roles_update'])->group(function (Router $router) {
        $router->put('role2/{id}', [OldRoleController::class, 'update'])->name('role2.update');
        $router->put('clone2/{id}', [OldRoleController::class, 'clone'])->name('role2.clone');
    });

    $router->middleware(['role:roles_delete'])->group(function (Router $router) {
        $router->delete('role2/{id}', [OldRoleController::class, 'delete'])->name('role2.delete');
    });

    $router->middleware(['role:pipeline_create'])->group(function (Router $router) {
        $router->get('pipeline/create', [PipelineController::class, 'create'])->name('pipeline.create');
        $router->post('pipeline',       [PipelineController::class, 'store'])->name('pipeline.store');
    });

    $router->middleware(['role:pipeline_list'])->group(function (Router $router) {
        $router->get('pipeline', [PipelineController::class, 'index'])->name('pipeline.index');
    });

    $router->get('pipeline/{id}', [PipelineController::class, 'show'])->name('pipeline.show');
    

    $router->middleware(['role:pipeline_edit'])->group(function (Router $router) {
        $router->put('pipeline/{id}', [PipelineController::class, 'update'])->name('pipeline.update');
    });

    //Subscription
    $router->middleware(['role:subscription_create'])->group(function (Router $router) {
        $router->get('subscription/create/{pipelineId}', [SubscriptionController::class, 'create'])->name('subscription.create');
        $router->post('subscription/store',       [SubscriptionController::class, 'store'])->name('subscription.store');
    });
    $router->middleware(['role:subscription_list'])->group(function (Router $router) {
        $router->get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    });
    $router->middleware(['role:subscription_edit'])->group(function (Router $router) {
        $router->get('subscription/edit/{id}', [SubscriptionController::class, 'edit'])->name('subscription.edit');
        $router->post('subscription/update/{id}', [SubscriptionController::class, 'update'])->name('subscription.update');
    });
    $router->middleware(['role:subscription_delete'])->group(function (Router $router) {
        $router->get('subscription/delete/{id}', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
    });
    /*
     * 'subscription_update' => 1,
                'subscription_list'   => 1,
                'subscription_show'   => 1,
     */
    
    
    // User Statistics Routes (only for user admin)
    $router->middleware(['role:statistics_view'])->group(function (Router $router) {
    $router->get('user-stats', [UserStatsController::class, 'index'])->name('user.stats');
    $router->get('user-stats/report', [UserStatsController::class, 'getUserReport'])->name('user.stats.report');
    $router->get('user-stats/client-details/{userId}/{status}', [UserStatsController::class, 'getClientDetails'])->name('user.stats.client.details');
    $router->get('user-stats/status-changed-clients/{userId}', [UserStatsController::class, 'getStatusChangedClients'])->name('user.stats.status.changed');
    $router->get('user-stats/live-updates', [UserStatsController::class, 'getLiveUpdates'])->name('user.stats.live.updates');
    $router->get('user-stats/target-progress', [UserStatsController::class, 'getDailyTargetProgress'])->name('user.stats.target.progress');
    $router->post('user-stats/transfer-clients', [UserStatsController::class, 'transferClients'])->name('user.stats.transfer.clients');
    $router->get('user-stats/latest-notification', [UserStatsController::class, 'getLatestNotification'])->name('user.stats.latest.notification');
    $router->get('user-stats/notifications', [UserStatsController::class, 'getNotifications'])->name('user.stats.notifications');
    });

    $router->middleware(['role:settings'])->group(function (Router $router) {
        $router->get('settings',        [SettingsController::class, 'index'])->name('settings.index');
        $router->put('settings/update', [SettingsController::class, 'update'])->name('settings.update');
        $router->put('settings/style',  [SettingsController::class, 'style'])->name('settings.style');
        $router->get('settings/edit-logo',  [SettingsController::class, 'editLogo'])->name('settings.editLogo');
        $router->post('settings.uploadLogo',  [SettingsController::class, 'uploadLogo'])->name('settings.uploadLogo');
    });
    
    $router->middleware(['role:overview'])->group(function (Router $router) {
        $router->get('overview/getLastComments', [OverviewController::class, 'getLastComments'])->name('overview.getLastComments');
        $router->get('overview/filter',          [OverviewController::class, 'filter'])->name('overview.filter');
        $router->get('overview',                 [OverviewController::class, 'index'])->name('overview.index');
    });

    $router->middleware(['role:reports_list'])->group(function (Router $router) {
        $router->get('reports', [ReportsController::class, 'index'])->name('reports.index');
    });

    $router->middleware(['role:requests_page_view'])->group(function (Router $router) {
        $router->get('request', [RequestController::class, 'index'])->name('request.index');
    });

    $router->middleware(['role:emails_template_list'])->group(function (Router $router) {
        $router->get('emails', [EmailsController::class, 'index'])->name('emails.index');
    });

    $router->middleware(['role:send_emails'])->group(function (Router $router) {
        $router->post('emails/send', [EmailsController::class, 'send'])->name('emails.send');
    });

    $router->middleware(['role:emails_template_create'])->group(function (Router $router) {
        $router->get('emails/create', [EmailsController::class, 'create'])->name('emails.create');
        $router->post('emails',       [EmailsController::class, 'store'])->name('emails.store');
    });

    $router->middleware(['role:emails_template_show'])->group(function (Router $router) {
        $router->get('emails/{id}', [EmailsController::class, 'show'])->name('emails.show');
    });

    $router->middleware(['role:emails_template_update'])->group(function (Router $router) {
        $router->put('emails/{id}', [EmailsController::class, 'update'])->name('emails.update');
    });

    $router->middleware(['role:emails_template_delete'])->group(function (Router $router) {
        $router->delete('emails/{id}', [EmailsController::class, 'delete'])->name('emails.delete');
    });

    $router->middleware(['role:emails_sender_email_list'])->group(function (Router $router) {
        $router->get('sender_emails', [SenderEmailsController::class, 'index'])->name('sender_emails.index');
    });

    $router->middleware(['role:emails_sender_emails_create'])->group(function (Router $router) {
        $router->get('sender_emails/create', [SenderEmailsController::class, 'create'])->name('sender_emails.create');
        $router->post('sender_emails',       [SenderEmailsController::class, 'store'])->name('sender_emails.store');
    });

    $router->middleware(['role:sender_email_show'])->group(function (Router $router) {
        $router->get('sender_emails/{id}', [SenderEmailsController::class, 'show'])->name('sender_emails.show');
    });

    $router->middleware(['role:emails_sender_emails_update'])->group(function (Router $router) {
        $router->put('sender_emails/{id}', [SenderEmailsController::class, 'update'])->name('sender_emails.update');
    });

    $router->middleware(['role:emails_sender_emails_delete'])->group(function (Router $router) {
        $router->delete('sender_emails/{id}', [SenderEmailsController::class, 'delete'])->name('sender_emails.delete');
    });

    $router->get('notification/mark_all_as_read', [NotificationController::class, 'mark_all_as_read'])->name('notification.mark_all_as_read');
    $router->put('user-profile/{id}',             [UserController::class, 'userprofile'])->name('user.edit');
    $router->get('user-profile',                  [UserController::class, 'userprofile'])->name('user.profile');
    $router->put('usdt/{id}',                     [PipelineController::class, 'updateUsdt'])->name('usdt.update');
    $router->get('home',                          [HomeController::class, 'index'])->name('home');
    
    
    //$router->middleware(['role:sender_email_update'])->group(function (Router $router) {
     //   $router->get('/', [SettingController::class, 'update'])->name('sender_emails.update');
    //});
    
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::post('client/{id}/export-data', [ClientsController::class, 'exportData'])->name('client.exportData');




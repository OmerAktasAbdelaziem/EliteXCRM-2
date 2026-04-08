<?php

use App\Http\Controllers\ClientsTransferController;
use App\Http\Controllers\LandingPagesController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\MainTPController;
use App\Http\Controllers\SmartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Models\Asset;
use App\Http\Controllers\Api\OrderApiController;


Route::get('/assets', function () {
    return response()->json(Asset::all());
});

Route::middleware(['check.api.key'])->group(function () {
    Route::get('/getFinancialData', [OrderApiController::class, 'getFinancialData']);
    Route::get('/calculatePnlWithoutOrder/{asset}/{orderType}/{openPrice}/{currentPrice}/{amount}', [OrderApiController::class, 'calculatePnlWithoutOrder']);
    Route::get('/getRequiredMargin/{asset}', [OrderApiController::class, 'getRequiredMargin']);
});

Route::name('api.')->prefix('v1/')->group(function (Router $router) {
    $router->post('LeadCapture/{source?}/{pipeline_id?}', [LandingPagesController::class,    'LeadCapture'])->name('LeadCapture');
    $router->post('smart/registerUser',                   [ClientsTransferController::class, 'register_user'])->name('register_user');
    $router->get('getFinancialData/{broker_id}',          [MainTPController::class,          'get_financial_data'])->name('get_financial_data');
    $router->post('smart/updateUserPassword',             [SmartController::class,           'update_user_password'])->name('update_user_password');
    $router->get('getOpenedData/{broker_id}',             [MainTPController::class,          'get_opened_data'])->name('get_opened_data');
    $router->get('getClosedData/{broker_id}/{from}/{to}', [MainTPController::class,          'get_closed_data'])->name('get_closed_data');
});

Route::name('telegram.')->prefix('telegram')->controller(TelegramController::class)->group(function (Router $router) {
    $router->name('webhooks.')->prefix('webhooks')->group(function (Router $router) {
        $router->post('/inbound', 'inbound')->name('inbound');
    });

    $router->name('telegramWebhooks.')->prefix('telegramWebhooks')->group(function (Router $router) {
        $router->post('/index', 'index')->name('index');
    });

    $router->name('telegramNotification.')->prefix('telegramNotification')->group(function (Router $router) {
        $router->post('/notification', 'notification')->name('notification');
    });

    $router->name('notifi.')->prefix('notifi')->group(function (Router $router) {
        $router->post('/notifi', 'notifi')->name('notifi');
        $router->post('/get', 'getId')->name('get');
    });
});

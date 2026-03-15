<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use App\Models\Client;
use App\Models\Message;
use App\Models\MoneyTrx;
use App\Models\Pipeline;
use App\Models\SystemStyle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::user()) {
                //$user_controller = new UserController;
                $nav_pipelines   = Pipeline::select('id', 'name')->where('id','!=',Auth::user()->pipeline_id)->get();
                $userService = app(\App\Http\Services\User\Interfaces\UserServiceInterface::class);
               // $options         = $userService->getUserOptions(Auth::user());//die;//$user_controller->get_user_options();
                $system          = SystemStyle::first();
                $notifications   = Client::where('user_id', Auth::user()->id)->where('is_notified', 1)->orderby('notified_at')->get();
                $totalRequests   = MoneyTrx::whereHas('client', function ($query) {
                    $query->where('pipeline_id', auth()->user()->pipeline_id);
                })->where('status', 'pending')->count();
    
                $view->with([
                    'totalRequests' => $totalRequests,
                    'nav_pipelines' => $nav_pipelines,
                    'notifications' => $notifications,
                   // 'options'       => $options,
                    'system'        => $system,
                ]);
            }
        });
    }
}

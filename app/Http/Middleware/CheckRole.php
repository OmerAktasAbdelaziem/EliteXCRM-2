<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\User\Interfaces\UserServiceInterface;
use UserPermission;

class CheckRole
{
   /* protected $userService;

    public function __construct(
            UserServiceInterface $userService,
            ) {
        $this->userService = $userService;
        
    }*/
    /*public function handle(Request $request, Closure $next, ...$roles)
    {//dd();
        $pipelineId = Auth::user()->pipeline_id;
        $hasPermission = false;
        foreach($roles as $role){
        $hasPermission = UserPermission::hasPermissionInPipeline(Auth::user(),$pipelineId , $role);
        if($hasPermission){
            break;
        }
        }
        $superAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        //dd($pipelineId);
        if ($hasPermission || $superAdmin || $pipelineAdmin) {
                return $next($request);
            }
    //dd($roles);
    //dd($next($request));
        //$user_controller = new UserController;
    

     

        abort(403, 'Unauthorized');
    }
    */
    public function handle(Request $request, Closure $next, ...$roles)
{
    $user = Auth::user();
    if (!$user) abort(403, 'Unauthorized');

    $pipelineId = $user->pipeline_id;
    
    // استخدام Cache لتخزين صلاحيات المستخدم لمدة 60 دقيقة
    $cacheKey = "user_permissions_{$user->id}_{$pipelineId}";
    
    $isAuthorized = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($user, $pipelineId, $roles) {
        
        // التحقق من الصلاحيات الأساسية
        foreach($roles as $role){
            if (UserPermission::hasPermissionInPipeline($user, $pipelineId, $role)) {
                return true;
            }
        }

        // التحقق من الأدوار الإدارية
        if (UserPermission::isSuperAdmin($user) || UserPermission::isPipelineAdmin($user, $pipelineId)) {
            return true;
        }

        return false;
    });

    if ($isAuthorized) {
        return $next($request);
    }

    abort(403, 'Unauthorized');
}
}

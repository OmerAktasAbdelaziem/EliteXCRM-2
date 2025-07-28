<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\User\Interfaces\UserServiceInterface;

class CheckRole
{
    protected $userService;

    public function __construct(
            UserServiceInterface $userService,
            ) {
        $this->userService = $userService;
        
    }
    public function handle(Request $request, Closure $next, ...$roles)
    {
        //$user_controller = new UserController;
        $options         = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();

        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        foreach ($roles as $role) {
            if (isset($options[$role])) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}

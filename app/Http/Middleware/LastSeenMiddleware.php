<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LastSeenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    /*public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);
            $user->lastseen_at = Carbon::now();
            $user->save();
        }
        return $next($request);
    }*/
    public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();
        
        // استخدم الذاكرة المؤقتة (Cache) بدلاً من قاعدة البيانات في كل طلب
        $cacheKey = 'user_last_seen_' . $user->id;
        
        // إذا لم يتم تحديث المستخدم في آخر 5 دقائق، قم بالتحديث
        if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            $user->lastseen_at = Carbon::now();
            $user->save();
            
            // ضع علامة في الكاش لمدة 5 دقائق
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, 300);
        }
    }
    return $next($request);
}
}

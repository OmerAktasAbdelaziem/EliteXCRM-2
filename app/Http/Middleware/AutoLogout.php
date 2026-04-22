<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity_time');
            $now = now();

            if ($lastActivity) {
                $inactive = $now->diffInSeconds(\Carbon\Carbon::parse($lastActivity));

                /*if ($inactive > 1800) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/login')->with('status', 'تم تسجيل خروجك تلقائياً بسبب عدم النشاط لأكثر من 30 دقيقة');
                }*/
            }

            session(['last_activity_time' => $now]);
        }

        return $next($request);
    }
}

<?php
/*
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

                if ($inactive > 28800) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/login')->with('status', 'تم تسجيل خروجك تلقائياً بسبب عدم النشاط لوقت طويل');
                }
            }

            session(['last_activity_time' => $now]);
        }

        return $next($request);
    }
}*/


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
            $currentTime = time(); 

            if ($lastActivity) {
                $inactiveSeconds = $currentTime - $lastActivity;

                if ($inactiveSeconds > 28800) { // 8 ساعات
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    // إذا كان الطلب من أجاكس، لا تفعل Redirect عادي، بل أرسل استجابة 401
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['message' => 'Session timeout.'], 401);
                    }

                    // التوجيه العادي للطلبات العادية
                    return redirect('/login')->with('status', 'تم تسجيل خروجك تلقائياً بسبب عدم النشاط لوقت طويل.');
                }
            }

            // لا نحدث وقت النشاط إذا كان الطلب أجاكس لكي لا يتجدد الوقت تلقائياً
            if (!$request->ajax() && !$request->wantsJson()) {
                session(['last_activity_time' => $currentTime]);
            }
        }

        return $next($request);
    }
}

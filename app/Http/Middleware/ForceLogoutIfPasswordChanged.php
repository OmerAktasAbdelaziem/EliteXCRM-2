<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ForceLogoutIfPasswordChanged
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $passwordChangedAt = Auth::user()->password_changed_at;
            $sessionPasswordChangedAt = session('password_changed_at');

            if ($passwordChangedAt && (!$sessionPasswordChangedAt || Carbon::parse($passwordChangedAt)->gt(Carbon::parse($sessionPasswordChangedAt)))) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors(['message' => 'تم تغيير كلمة المرور من جهاز آخر، الرجاء تسجيل الدخول مرة أخرى.']);
            }
        }

        return $next($request);
    }
}

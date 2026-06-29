<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ForceLogoutIfPasswordChanged
{
   /* public function handle($request, Closure $next)
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
    }*/
    public function handle($request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();
        
        // احصل على الوقت من قاعدة البيانات فقط إذا لم يكن مخزناً في الجلسة أو كان هناك شك
        $passwordChangedAt = $user->password_changed_at;
        
        // إذا لم يكن التاريخ موجوداً في الجلسة، قم بتخزينه (مرة واحدة فقط عند تسجيل الدخول)
        if (!session()->has('password_changed_at')) {
            session(['password_changed_at' => $passwordChangedAt]);
        }

        $sessionPasswordChangedAt = session('password_changed_at');

        if ($passwordChangedAt && (!$sessionPasswordChangedAt || Carbon::parse($passwordChangedAt)->gt(Carbon::parse($sessionPasswordChangedAt)))) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['message' => 'تم تغيير كلمة المرور، يرجى تسجيل الدخول.']);
        }
    }

    return $next($request);
}
}

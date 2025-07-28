<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function logout()
    {
        $auth = User::find(Auth::user()->id);

        $auth->lastlogout_at = Carbon::now();

        $auth->save();

        auth()->logout();

        return redirect('/login');
    }
    protected function authenticated(Request $request, $user)
    {
        $auth = User::find(Auth::user()->id);

        $auth->lastlogin_at = Carbon::now();

        $auth->save();

        session(['password_changed_at' => Auth::user()->password_changed_at]);
    }

    protected function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];
    }
}

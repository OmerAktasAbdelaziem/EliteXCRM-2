<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    protected function index()
    {
        $auth = User::find(Auth::user()->id);

        $auth->lastlogout_at = Carbon::now();

        $auth->save();

        auth()->logout();

        return redirect('/login');
    }
}

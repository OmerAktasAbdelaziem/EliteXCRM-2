<?php

namespace App\Http\Controllers;

use App\Models\SystemStyle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'maleavatar'     => ['nullable' , 'image' , 'max:2048'],
            'femaleavatar'   => ['nullable' , 'image' , 'max:2048'],
        ]);

        $maleavatar   = $request->file('maleavatar');
        $femaleavatar = $request->file('femaleavatar');

        $system = SystemStyle::first();

        if ($maleavatar != null) {
            if ($system->malePic) {
                Storage::delete($system->malePic);
            }
            $path = $maleavatar->store('public/avatars');
            $system->malePic = $path;
        }

        if ($femaleavatar != null) {
            if ($system->femalePic) {
                Storage::delete($system->femalePic);
            }
            $path = $femaleavatar->store('public/avatars');
            $system->femalePic = $path;
        }

        $system->update();

        return redirect()->route('settings.index');
    }

    public function style(Request $request)
    {
        $request->validate([
            'darkstyle' => ['nullable'],
        ]);

        $darkstyle   = $request->input('darkstyle');

        $user = User::findOrfail(Auth::id());

        if ($darkstyle != null) {
            $user->style = 'dark';
        }else{
            $user->style = 'light';
        }

        $user->save();

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SystemStyle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

//Services
use App\Http\Services\Organization\Interfaces\PipelineServiceInterface;

class SettingsController extends Controller
{
    
    protected $pipelineService;
    public function __construct(
            PipelineServiceInterface $pipelineService,
            ) {
        $this->pipelineService = $pipelineService;
        
    }
    
    /*public function index()
    {
        return view('settings');
    }*/

    
    public function index()
    {
       
        
        
        
        return view('settings.index');
    }
    public function editLogo() {
        return view('settings.editLogo');
        
    }

    public function uploadLogo(Request $request){
        
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        

        // upload image
        $path = $request->file('logo')->store('pipeline/logos', 'public');

        //Save in database
        //$pipeline->logo = $path;
        //$pipeline->save();
        $this->pipelineService->update(Auth::user()->pipeline_id, ['logo'=>$path]);

        return back()->with('success', 'Image uploaded successfully');
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

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

        

if(Auth::user()->pipeline?->co_id == Auth::id()){
        // upload image
        $path = $request->file('logo')->store('pipeline/'.Auth::user()->username.'/logos/', 'public');

        //Save in database
        //$pipeline->logo = $path;
        //$pipeline->save();
        
        //first check if user is admin for pipeline to use co_id in pipelines table instead of pipeline_id in user
    
        $this->pipelineService->update(Auth::user()->pipeline_id, ['logo'=>$path]);

        return back()->with('success', 'Image uploaded successfully');
}else{
    return redirect()->back()->withErrors('You dont have permission to edit logo');
}
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

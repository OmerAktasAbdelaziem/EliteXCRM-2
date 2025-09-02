<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePartRequest;
use App\Models\Part;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::latest()->get();

        return view('part.index',compact(
            'parts'
        ));
    }
    
    public function create()
    {
        $part  = new Part;
        $teams = Team::latest()->get();
        $users = User::WithPipeline()->latest()->get();
      //  $roles = OldRole::latest()->get();
        return view('part.show',compact(
          //  'roles',
            'teams',
            'users',
            'part',
        ));
    }
    
    public function store(CreatePartRequest $request)
    {
        $inputs = $request->only([
            'leader_id',
            'role_id',
            'name',
        ]);

        if (Auth::user()->pipeline->part_limit && Auth::user()->pipeline->part_limit <= Part::where('pipeline_id', Auth::user()->pipeline_id)->count()) {
            return redirect()->route('part.index')->with('fail','Part Limit Reached');
        }


        $part = Part::Create($inputs);

        if ($request->teams) {
            Team::whereIn('id', $request->teams)->update(['part_id' => $part->id]);
        }

        return redirect()->route('part.index')->with('success','Part Created Successfully');
    }
    
    public function show($id)
    {
        $part  = Part::findOrfail($id);
        $teams = Team::latest()->get();
        $users = User::WithPipeline()->latest()->get();
     //   $roles = OldRole::latest()->get();
        return view('part.show',compact(
          //  'roles',
            'teams',
            'users',
            'part',
        ));
    }
    
    public function update(Request $request, $id)
    {
        $part = Part::findOrFail($id);
        $request->validate([
            'leader_id' => ['nullable' , 'numeric' , 'exists:users,id'],
            'role_id'   => ['nullable' , 'numeric' , 'exists:roles,id'],
            'teams.*'   => ['nullable' , 'numeric' , 'exists:teams,id'],
            'teams'     => ['nullable' , 'array'],
            'name'      => ['required' , 'string' , 'unique:parts,name,'.$id.',id,pipeline_id,'.Auth::user()->pipeline_id],
        ]);

        $inputs = $request->only([
            'leader_id',
            'role_id',
            'name',
        ]);

        $part->update($inputs);

        Team::where('part_id', $part->id)->update(['part_id' => null]);

        if ($request->teams) {
            Team::whereIn('id', $request->teams)->update(['part_id' =>  $part->id]);
        }

        return redirect()->back()->with('success','Part Updated Successfully');
    }
}

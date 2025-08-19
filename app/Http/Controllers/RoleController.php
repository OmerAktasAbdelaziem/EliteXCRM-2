<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Models\OldRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = OldRole::select('id','name','created_at')->latest()->get();
        return view('role.index',compact(
            'roles'
        ));
    }
    
    public function create()
    {
        $role  = new OldRole;
        $teams = Team::latest()->get();
        $users = User::WithPipeline()->latest()->get();
        $parts = Part::latest()->get();
        
        return view('role2.show',compact(
            'parts',
            'teams',
            'users',
            'role',
        ));
    }
    
    public function store(CreateRoleRequest $request)
    {
        // dd($request->all());

        $inputs = $request->only(['name']);
        $inputs['options'] = json_encode($request->input('options'));

        $role = OldRole::Create($inputs);

        if ($request->teams) {
            Team::whereIn('id', $request->teams)->update(['role_id' => $role->id]);
        }
        if ($request->users) {
            $users = User::WithPipeline()->whereIn('id', $request->users)->get();
                foreach ($users as $user) {
                $roleIds     = json_decode($user->role_ids, true) ?? [];
                $userRole    = OldRole::whereIn('id', $roleIds)->first();
                if ($userRole) {
                    $roleIds = array_filter($roleIds, function($id) use ($userRole) {
                        return $id != $userRole->id;
                    });
                }
                
                $role_ids = array_merge($roleIds, [$role->id]);
                $user->update(['role_ids' => $role_ids]);
            }
        }
        if ($request->parts) {
            Part::whereIn('id', $request->parts)->update(['role_id' => $role->id]);
        }

        return redirect()->route('role.index');
    }
    
    public function show($id)
    {
        $role  = OldRole::findOrfail($id);
       // $teams = Team::latest()->get();
        //$users = User::WithPipeline()->latest()->get();
        //$parts = Part::latest()->get();
       // if (is_string($role->options)) {
           // $role->options = json_decode($role->options, true);
       // }

        return view('role2.show',compact(
          //  'parts',
            //'teams',
          //  'users',
            'role',
        ));
    }
    
    public function update(Request $request, $id)
    {
        // dd($request->all()); 

        $role = OldRole::findOrFail($id);
        $request->validate([
            'name'    => ['required' , 'string' , 'unique:roles,name,'.$id.',id,pipeline_id,'.Auth::user()->pipeline_id],
            'users.*' => ['nullable' , 'numeric' , 'exists:users,id'],
            'teams.*' => ['nullable' , 'numeric' , 'exists:teams,id'],
            'parts.*' => ['nullable' , 'numeric' , 'exists:parts,id'],
            'users'   => ['nullable' , 'array'],
            'teams'   => ['nullable' , 'array'],
            'parts'   => ['nullable' , 'array'],
        ]);

        $inputs = $request->only(['name']);
        $inputs['options'] = json_encode($request->input('options'));

        $role->update(['options' => '']);
        $role->update($inputs);

        Team::where('role_id', $role->id)->update(['role_id' => null]);
        Part::where('role_id', $role->id)->update(['role_id' => null]);

        if ($request->teams) {
            Team::whereIn('id', $teams)->update(['role_id' => $role->id]);
        }
        
        if ($request->parts) {
            Part::whereIn('id', $parts)->update(['role_id' => $role->id]);
        }

        $users = User::whereNotNull('role_ids')
    ->where('role_ids', '!=', '')
    ->whereJsonContains('role_ids', $role->id)
    ->latest()
    ->get();

        if ($users) {
            foreach ($users as $user) {
                $roleIds = json_decode($user->role_ids, true) ?? [];
                $roleIds = array_filter($roleIds, function($id) use ($role) {
                    return $id != $role->id;
                });

                $user->update(['role_ids' => $roleIds]);
            }
        }

        if ($request->users) {
            //print_r($request->users);die;
            $users = User::WithPipeline()->whereIn('id', $request->users)->get();
            foreach ($users as $user) {
                $roleIds     = json_decode($user->role_ids, true) ?? [];
                $userRole    = OldRole::whereIn('id', $roleIds)->first();
                if ($userRole) {
                    $roleIds = array_filter($roleIds, function($id) use ($userRole) {
                        return $id != $userRole->id;
                    });
                }
                $roleIds = array_merge($roleIds, [$role->id]);

                $user->update(['role_ids' => $roleIds]);
            }
        }
        
        return redirect()->route('role.show', $id)->with('success', 'Role updated successfully');
    }

    public function clone(Request $request, $id)
    {
        $role = OldRole::findOrFail($id);

        $new = OldRole::create([
            'pipeline_id' => $role->pipeline_id,
            'options'     => $role->options,
            'name'        => $role->name . ' Copy',
        ]);
        
        return redirect()->route('role.show', $new->id)->with('success', 'Role cloned successfully');
    }

    public function delete($id)
    {
        $role = OldRole::findOrFail($id);
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role deleted successfully');
    }
}

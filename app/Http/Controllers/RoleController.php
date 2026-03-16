<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


use App\Http\Services\Role\Interfaces\RoleServiceInterface;
use App\Http\Services\Role\Interfaces\PermissionServiceInterface;

use App\Facades\UserPermission;
    


    




class RoleController extends Controller
{
    protected $roleService;
    protected $permissionService;
    public function __construct(
            RoleServiceInterface $roleService,
            PermissionServiceInterface $permissionService,

            ) {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;

        
    }
    
    
    public function index()
    {
        //$roles = Role::select('id','name','created_at')->latest()->get();
        $roles = $this->roleService->getByFilters([
            ['field'=>'pipeline','conditions'=>['='=>Auth::user()->pipeline_id]]
        ]);
        
        return view('role.index',compact(
            'roles'
        ));
    }
    
    public function create()
    {
       /*$role  = new OldRole;
        $teams = Team::latest()->get();
        $users = User::WithPipeline()->latest()->get();
        $parts = Part::latest()->get();*/
        
        return view('role.create'/*,compact(
            'parts',
            'teams',
            'users',
            'role',
        )*/);
    }
    
    public function store(CreateRoleRequest $request)
    {
     
$permissions = array_keys($request['roles']);
//dd($permissions);
 $this->roleService->create($request->name,Auth::user()->pipeline_id,$permissions);
/*
dd('a');
    return response()->json([
        'success' => true,
        'role' => $role
    ]);
        
        
         dd($request['roles']);

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
        }*/

        return redirect()->route('role.index');
    }
    
    public function show($id)
    {
        $role = $this->roleService->getByFilters(
                [['field'=>'id','conditions'=>['='=>$id]]], 
                ['permissions']
                );
        dd($role);
        $role  = OldRole::findOrfail($id);
       // $teams = Team::latest()->get();
        //$users = User::WithPipeline()->latest()->get();
        //$parts = Part::latest()->get();
       // if (is_string($role->options)) {
           // $role->options = json_decode($role->options, true);
       // }

        return view('role.create',compact(
          //  'parts',
            //'teams',
          //  'users',
            'role',
        ));
    }
    
    public function edit($id)
    {
        $pipelineId = Auth::user()->pipeline_id;
       // dd(UserPermission::hasPermissionInPipeline(Auth::user(),$pipelineId , 'open_order_show'));
       // dd(UserPermission::hasRoleInPipeline(Auth::user(),$pipelineId , 'super_admin'));
        
        $role = $this->roleService->getByFilters([['field'=>'id','conditions'=>['='=>$id]]], ['permissions'])->first();
   
            return view('role.edit', compact(
                        'role',
                        //'subscription',
                        //'brokers',
                        //'users',
                ));
    }
    
    public function update(Request $request, $id)
    {
        
        $pipelineId = Auth::user()->pipeline_id;
        $role = $this->roleService->getById($id)->first();
     

        $this->roleService->update($id,['name'=>$request->name]);
       

        $role->load('permissions');
        //dd($role);
        $permissions = array_keys($request['roles']);
        //$permissionsString = implode(',',$permissions);
        $existPermissions = $this->permissionService->getByFilters([
            ['field'=>'name','conditions'=>['in'=>$permissions]],
            ['field'=>'pipeline','conditions'=>['='=>$pipelineId]]
            ]);
        $existPermissionNames = $existPermissions->pluck('name')->toArray();
        $notExistPermissions = array_diff($permissions, $existPermissionNames);
        $newPermissionsData = [];
        foreach($notExistPermissions as $notExistPermission){
            $newPermissionsData[] = [
                'pipeline'=>$pipelineId,
                'guard_name'=>'web',
                'name'=>$notExistPermission,
                'created_at' => now(),
                'updated_at' => now(),
                ];
            //$this->permissionService->
        }
        //dd($existPermissions);
        $this->permissionService->createBulk($newPermissionsData);
        $syncPermissions = $this->permissionService->getByFilters([
            ['field'=>'name','conditions'=>['in'=>$permissions]],
            ['field'=>'pipeline','conditions' => ['='=>$pipelineId]],
        ]);
        $syncPermissionsIds = $syncPermissions->pluck('id')->toArray();
        //dd($syncPermissionsIds);
        $role->syncPermissions($syncPermissions);
        //die('a');
        // dd($request->all()); 

        /*$role = OldRole::findOrFail($id);
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
        }*/
        
        return redirect()->route('role.edit', $id)->with('success', 'Role updated successfully');
    }

    public function clone(Request $request, $id)
    {
        /*$role = OldRole::findOrFail($id);

        $new = OldRole::create([
            'pipeline_id' => $role->pipeline_id,
            'options'     => $role->options,
            'name'        => $role->name . ' Copy',
        ]);*/
        
        $originalRole = Role::findOrFail($id);

        
        $newName = $originalRole->name . ' Copy';

        //Create new role with same pipeline and guard
        $newRole = Role::create([
            'name'        => $newName,
            'guard_name'  => $originalRole->guard_name,
            'pipeline' => $originalRole->pipeline ?? null,
        ]);

        // copy all related permissions
        $permissions = $originalRole->permissions->pluck('id')->toArray();
        $newRole->permissions()->sync($permissions);
       
        
        return redirect()->route('role.edit', $newRole->id)->with('success', 'Role cloned successfully');
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role deleted successfully');
    }
}

<?php

namespace App\Http\Services\Role;

//Interfaces
use App\Http\Repositories\Role\Interfaces\RoleRepositoryInterface;
use App\Http\Services\Role\Interfaces\RoleServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

//Permissions
use App\Models\Role;
use App\Models\Permission;

use Spatie\Permission\Exceptions\PermissionDoesNotExist;


class RoleService implements RoleServiceInterface {

    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository) {
        $this->roleRepository = $roleRepository;
    }

    public function getAll(): Collection{
        $results = $this->roleRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->roleRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->roleRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(string $name,int $pipelineId ,array $permissions = []): void {
        //$pipelineId = 2;
        //$role = Role::create(['name' => $name,'pipeline'=>2]);//add pipeline
        $role = Role::where('name',  $name)
    ->where('guard_name', 'web')
    ->where('pipeline', $pipelineId)
    ->first();

if (!$role) {
    /*$role = Role::create([
        'name' => $name,
        'guard_name' => 'web',
        'pipeline' => 2,
    ]);*/
    $role = Role::findOrCreateWithPipeline($name,null,$pipelineId);
       if($permissions){
        foreach ($permissions as $permission) {
        //Permission::findOrCreate(['name' =>$permission,'pipeline'=>$pipelineId]);//add pipeline
            
            Permission::findOrCreateWithPipeline($permission,null,$pipelineId);
            /*$permissionModel = Permission::where('name', $permission)
    ->where('pipeline', $pipelineId)
    ->first();*/

 /*if (! $permissionModel) {
    
   $permissionModel = Permission::create([
        'name' => $permission,
        'pipeline' =>$pipelineId,
        'guard_name' => 'web', 
    ]);
}*/
    }
    $role->givePermissionTo($permissions);
       }
       }
       // return $this->roleRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        
        return $this->roleRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->roleRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->roleRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->roleRepository->deleteByIDs($Ids);
    }
   /* public function safeHasPermission(Role $role, string $permission): bool
{
    try {
        return $role->hasPermissionTo($permission);
    } catch (PermissionDoesNotExist $e) {
        return false;
    }
}*/
public function safeHasPermission(Role $role, string $permission): bool
{
    return $role->permissions->contains('name', $permission);
}
    
    
}
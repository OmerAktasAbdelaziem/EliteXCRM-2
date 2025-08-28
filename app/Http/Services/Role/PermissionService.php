<?php

namespace App\Http\Services\Role;

//Interfaces
use App\Http\Repositories\Role\Interfaces\PermissionRepositoryInterface;
use App\Http\Services\Role\Interfaces\PermissionServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

//Permissions
use App\Models\Permission;




class PermissionService implements PermissionServiceInterface {

    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository) {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAll(): Collection{
        $results = $this->permissionRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->permissionRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->permissionRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(string $name,int $pipelineId ,array $permissions): void {
        //$pipelineId = 2;
        //$permission = Permission::create(['name' => $name,'pipeline'=>2]);//add pipeline
        $permission = Permission::where('name',  $name)
    ->where('guard_name', 'web')
    ->where('pipeline', $pipelineId)
    ->first();

if (!$permission) {
    /*$permission = Permission::create([
        'name' => $name,
        'guard_name' => 'web',
        'pipeline' => 2,
    ]);*/
    $permission = Permission::findOrCreateWithPipeline($name,null,$pipelineId);
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
    $permission->givePermissionTo($permissions);
       }
       }
       // return $this->permissionRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        
        return $this->permissionRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->permissionRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->permissionRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->permissionRepository->deleteByIDs($Ids);
    }
    
    
    
}
<?php

namespace App\Http\Services\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PipelineRepositoryInterface;
use App\Http\Services\Organization\Interfaces\PipelineServiceInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Role\Interfaces\RoleServiceInterface;
use App\Http\Services\Asset\Interfaces\AssetGroupServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;


class PipelineService implements PipelineServiceInterface {

    protected $pipelineRepository;
    protected $userService;
    protected $roleService;
    protected $assetGroupService;

    public function __construct(PipelineRepositoryInterface $pipelineRepository,
    UserServiceInterface $userService,
    RoleServiceInterface $roleService,
    AssetGroupServiceInterface $assetGroupService,
    ) {
        $this->pipelineRepository = $pipelineRepository;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->assetGroupService = $assetGroupService;
    }
    
    public function getAll(): Collection{
        $results = $this->pipelineRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->pipelineRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->pipelineRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        $adminId = $data['co_id'];
        unset($data['co_id']);
        //dd($data)
        $pipeline = $this->pipelineRepository->create($data)->first();
        $pipelineId = $pipeline->id;

   $user = $this->userService->getById($adminId)->first();
   /*$role = Role::firstOrCreate([
    'name' => 'pipeline_admin',
    'guard_name' => 'web',
    'pipeline' => $pipelineId
]);*/
$this->roleService->create('pipeline_admin',$pipelineId);
$this->assetGroupService->cloneAssetGroup($pipelineId);
        $user->assignRoleWithPipeline('pipeline_admin', $pipelineId);
        $user->pipeline_id = $pipelineId;
        $user->save();
 

  /*  $user->roles()->attach($role->id, [
        'pipeline_id' => $pipelineId
    ]);*/

    return new Collection([$pipeline]);
    }
    
    public function update(int $id,array $data):int
    {
       // return $this->pipelineRepository->update($id, $data);
        if(isset($data['co_id'])){
           $adminId = $data['co_id'];
           unset($data['co_id']);
        }
   
       $result = $this->pipelineRepository->update($id,$data);
   
       $pipelineId = $id;
   
     
      /* $role = Role::firstOrCreate([
           'name' => 'pipeline_admin',
           'guard_name' => 'web',
           'pipeline' => $pipelineId
       ]);*/

       $role = $this->roleService->getByFilters([
        [
            'field' => 'name',
            'conditions' => ['=' => 'pipeline_admin']
        ],
        [
            'field' => 'pipeline',
            'conditions' => ['=' => $pipelineId]
        ]
    ])->first();
  // dd($role->id);
    
       \DB::table('rl_model_has_roles')
           ->where('role_id',$role->id)
           ->where('pipeline_id',$pipelineId)
           ->delete();
   
      
     //  $user = User::find($adminId);
     
    if(isset($adminId)){

        $user = $this->userService->getById($adminId)->first();
   
        $user->roles()->attach($role->id,[
            'pipeline_id'=>$pipelineId,
            'model_type'=>  \App\Models\User::class
        ]);
   
    }

       return $result;
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->pipelineRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->pipelineRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->pipelineRepository->deleteByIDs($params);
    }
    
    
}
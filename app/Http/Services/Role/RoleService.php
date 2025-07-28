<?php

namespace App\Http\Services\Role;

//Interfaces
use App\Http\Repositories\Role\Interfaces\RoleRepositoryInterface;
use App\Http\Services\Role\Interfaces\RoleServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

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
    public function create(array $data): Collection {
        return $this->roleRepository->create($data);
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
    
    
}
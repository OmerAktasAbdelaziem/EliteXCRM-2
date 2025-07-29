<?php

namespace App\Http\Services\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PartRepositoryInterface;
use App\Http\Services\Organization\Interfaces\PartServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class PartService implements PartServiceInterface {

    protected $partRepository;

    public function __construct(PartRepositoryInterface $partRepository) {
        $this->partRepository = $partRepository;
    }

    public function getAll(): Collection{
        $results = $this->partRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->partRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->partRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->partRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->partRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->partRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->partRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->partRepository->deleteByIDs($Ids);
    }
    
    
}
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

    public function getByParams(array $params): ?Collection{
        $results = $this->partRepository->getByParams($params);
        return $$results;
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
        return $this->partRepository::insert($data);
    }

    public function deleteByParams(array $params): int {
        return $this->partRepository->deleteByIDs($Ids);
    }
    
    
}
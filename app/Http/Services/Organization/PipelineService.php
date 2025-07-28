<?php

namespace App\Http\Services\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PipelineRepositoryInterface;
use App\Http\Services\Organization\Interfaces\PipelineServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class PipelineService implements PipelineRepositoryInterface {

    protected $pipelineRepository;

    public function __construct(PipelineServiceInterface $pipelineRepository) {
        $this->pipelineRepository = $pipelineRepository;
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
        return $this->pipelineRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->pipelineRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->pipelineRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->pipelineRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->pipelineRepository->deleteByIDs($Ids);
    }
    
    
}
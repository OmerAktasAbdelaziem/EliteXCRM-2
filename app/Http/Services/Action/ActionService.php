<?php

namespace App\Http\Services\Action;

//Interfaces
use App\Http\Repositories\Action\Interfaces\ActionRepositoryInterface;
use App\Http\Services\Action\Interfaces\ActionServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class ActionService implements ActionServiceInterface {

    protected $actionRepository;

    public function __construct(ActionRepositoryInterface $actionRepository) {
        $this->actionRepository = $actionRepository;
    }

    public function getAll(): Collection{
        $results = $this->actionRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->actionRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->actionRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->actionRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->actionRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->actionRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->actionRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->actionRepository->deleteByIDs($Ids);
    }
    
    
}
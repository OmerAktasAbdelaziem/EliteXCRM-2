<?php

namespace App\Http\Services\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\TeamRepositoryInterface;
use App\Http\Services\Organization\Interfaces\TeamServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class TeamService implements TeamServiceInterface {

    protected $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository) {
        $this->teamRepository = $teamRepository;
    }

    public function getByParams(array $params): ?Collection{
        $results = $this->teamRepository->getByParams($params);
        return $$results;
    }
    public function create(array $data): Collection {
        return $this->teamRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->teamRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->teamRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->teamRepository::insert($data);
    }

    public function deleteByParams(array $params): int {
        return $this->teamRepository->deleteByIDs($Ids);
    }
    
    
}
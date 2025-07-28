<?php

namespace App\Http\Services\Client;

//Interfaces
use App\Http\Repositories\Client\Interfaces\ClientRepositoryInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class ClientService implements ClientServiceInterface {

    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository) {
        $this->clientRepository = $clientRepository;
    }

    public function getAll(): Collection{
        $results = $this->clientRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->clientRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params): Collection{
        $results = $this->clientRepository->getByFilters($params);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->clientRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->clientRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->clientRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->clientRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->clientRepository->deleteByIDs($Ids);
    }
    
    
}
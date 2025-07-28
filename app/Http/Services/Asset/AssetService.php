<?php

namespace App\Http\Services\Asset;

//Interfaces
use App\Http\Repositories\Asset\Interfaces\AssetRepositoryInterface;
use App\Http\Services\Asset\Interfaces\AssetServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class AssetService implements AssetServiceInterface {

protected $assetRepository;
    public function __construct(AssetRepositoryInterface $assetRepository) {
        $this->assetRepository = $assetRepository;
    }
    public function getAll(): Collection{
        $results = $this->assetRepository->getAll();
        return $results;
    }
    public function getById(int $id): Collection{
        $results = $this->assetRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params): Collection{
        $results = $this->assetRepository->getByFilters($params);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->assetRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->assetRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->assetRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->assetRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->assetRepository->deleteByIDs($Ids);
    }
    
    
}
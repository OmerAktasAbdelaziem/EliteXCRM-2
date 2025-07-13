<?php

namespace App\Http\Services\Asset;

//Interfaces
use App\Http\Repositories\Asset\Interfaces\AssetGroupRepositoryInterface;
use App\Http\Repositories\Asset\Interfaces\AssetGroupAssignmentRepositoryInterface;
use App\Http\Services\Asset\Interfaces\AssetGroupServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class AssetGroupService implements AssetGroupServiceInterface {

protected $assetGroupRepository;
protected $assetGroupAssignmentRepository;
    public function __construct(AssetGroupRepositoryInterface $assetGroupRepository,
            AssetGroupAssignmentRepositoryInterface $assetGroupAssignmentRepository
            ) {
        $this->assetGroupRepository = $assetGroupRepository;
        $this->assetGroupAssignmentRepository = $assetGroupAssignmentRepository;
    }

    public function getAll(): Collection{
        $results = $this->assetGroupRepository->getAll();
        return $results;
    }
    public function getById(int $id): Collection{
        $results = $this->assetGroupRepository->getById($id);
        return $results;
    }
    public function getByFilters(array $params): Collection{
        $results = $this->assetGroupRepository->getByFilters($params);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->assetGroupRepository->create($data);
    }
    public function createWithAssets(array $data): Collection {
        return DB::transaction(function () use ($data) {
         $group = $this->assetGroupRepository->create($data['assetGroup']);
         $assignments = [];

            foreach ($data['assets'] as $asset) {
                $assignments[] = [
                    'asset_group'     => $group->id,
                    'asset'           => $asset['asset_id'],
                    'size'            => $asset['size'] ?? null,
                    'leverage'        => $asset['leverage'] ?? null,
                    'bid_spread'      => $asset['bid_spread'] ?? null,
                    'ask_spread'      => $asset['ask_spread'] ?? null,
                    'buy_commission'  => $asset['buy_commission'] ?? null,
                    'sell_commission' => $asset['sell_commission'] ?? null,
                ];
            }
            $this->assetGroupAssignmentRepository->createBulk($data);
            return $group->load('assetAssignments.asset');
        });
    }
    
    public function update(int $id,array $data):int
    {
        return $this->assetGroupRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->assetGroupRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->assetGroupRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->assetGroupRepository->deleteByIDs($Ids);
    }
    
    public function getAssetGroupAssignmentById(int $id): Collection{
        $result = $this->assetGroupAssignmentRepository->getById($id);
        return $result;
    }
    
    public function createAssetGroupAssignment(array $params): Collection
    {
        return $this->assetGroupAssignmentRepository->create($params);
    }
    public function createBulkAssetGroupAssignment(array $params): Collection
    {
        return $this->assetGroupAssignmentRepository->createBulk($params);
    }
    public function updateAssetGroupAssignment(int $id, array $params):int
    {
        return $this->assetGroupAssignmentRepository->update($id, $params);
    }
    public function deleteAssetGroupAssignment(array $params): int
    {
        return $this->assetGroupAssignmentRepository->deleteByParams($params);
    }
    
    
    
}
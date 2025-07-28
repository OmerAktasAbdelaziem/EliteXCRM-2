<?php
namespace App\Http\Services\Asset\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;

interface AssetGroupServiceInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
     public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
     public function getAssetGroupAssignmentById(int $id): Collection;
     public function createAssetGroupAssignment(array $params): Collection;
     public function createBulkAssetGroupAssignment(array $params): Collection;
    public function updateAssetGroupAssignment(int $id, array $params):int;
    public function deleteAssetGroupAssignment(array $params): int;
}
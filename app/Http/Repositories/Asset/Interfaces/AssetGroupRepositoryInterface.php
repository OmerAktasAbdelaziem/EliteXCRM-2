<?php
namespace App\Http\Repositories\Asset\Interfaces;


//other
use Illuminate\Database\Eloquent\Collection;

use App\Models\AssetGroup;

interface AssetGroupRepositoryInterface{
    public function getAll(): Collection;
    public function cloneAssetGroup(int $newPipelineId): ?AssetGroup;
    public function getById(int $id): Collection;
   public function getByFilters(array $params,array $with = []): Collection;
   public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
}
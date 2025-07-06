<?php
namespace App\Http\Services\Order\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface{
    public function getByParams(array $params): ?Collection;
     public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
}
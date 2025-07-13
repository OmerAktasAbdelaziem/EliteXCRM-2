<?php
namespace App\Http\Repositories\Order\Interfaces;

//other
use Illuminate\Database\Eloquent\Collection;

interface MoneyTransactionRepositoryInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
   public function getByFilters(array $params): Collection;
   public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
}
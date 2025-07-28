<?php
namespace App\Http\Services\Order\Interfaces;

//Models 
use App\Models\Order;

//Other
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
     public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
     public function calculatePnl(Order $order,int $commands = 0):int;
     public function getFinancialData(int $brokerId): array;
     public function getClosedOrdersPL(int $brokerId):float;
}
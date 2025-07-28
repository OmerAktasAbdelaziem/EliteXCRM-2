<?php
namespace App\Http\Services\Order\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;

interface MoneyTransactionServiceInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
     public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
     public function getDeposits(int $brokerId): float;
     public function getLastDeposit(int $brokerId): Collection;
     public function getWithdrawals(int $brokerID):float;
     public function getCreditIn(int $brokerID):float;
     public function getCreditOut(int $brokerID):float;
     public function getBonusIn(int $brokerID):float;
     public function getBonusOut(int $brokerID):float;
     public function getPendingWithdrawal(int $brokerId):float;
     
}
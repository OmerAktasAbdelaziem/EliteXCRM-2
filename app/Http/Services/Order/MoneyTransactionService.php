<?php

namespace App\Http\Services\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\MoneyTransactionRepositoryInterface;
use App\Http\Services\Order\Interfaces\MoneyTransactionServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class MoneyTransactionService implements MoneyTransactionServiceInterface {

    protected $moneyTransaction;

    public function __construct(MoneyTransactionRepositoryInterface $moneyTransaction) {
        $this->moneyTransaction = $moneyTransaction;
    }
    public function getAll(): Collection{
        $results = $this->moneyTransaction->getAll();
        return $results;
    }

    public function getById(int $id): Collection{
        $results = $this->moneyTransaction->getById($id);
        return $results;
    }
    public function getByFilters(array $params): Collection{
        $results = $this->moneyTransaction->getByFilters($params);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->moneyTransaction->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->moneyTransaction->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->moneyTransaction->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->moneyTransaction->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->moneyTransaction->deleteByIDs($Ids);
    }
    
    
}
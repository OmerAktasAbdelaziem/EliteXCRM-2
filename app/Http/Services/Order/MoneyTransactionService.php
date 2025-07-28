<?php

namespace App\Http\Services\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\MoneyTransactionRepositoryInterface;
use App\Http\Services\Order\Interfaces\MoneyTransactionServiceInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class MoneyTransactionService implements MoneyTransactionServiceInterface {

    protected $moneyTransaction;
    protected $clientService;

    public function __construct(MoneyTransactionRepositoryInterface $moneyTransaction,
            ClientServiceInterface $clientService
            ) {
        $this->moneyTransaction = $moneyTransaction;
        $this->clientService = $clientService;
    }
    public function getAll(): Collection{
        $results = $this->moneyTransaction->getAll();
        return $results;
    }

    public function getById(int $id): Collection{
        $results = $this->moneyTransaction->getById($id);
        return $results;
    }
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->moneyTransaction->getByFilters($params,$with);
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
    
    public function getDeposits(int $brokerId): float
    {
        return $this->moneyTransaction->getDeposits($brokerId);
    }
    public function getLastDeposit(int $brokerId): Collection
    {
        return $this->moneyTransaction->getLastDeposit($brokerId);
    }
    public function getWithdrawals(int $brokerID):float
    {
        return $this->moneyTransaction->getWithdrawals($brokerID);
    }
    
    public function getCreditIn(int $brokerID):float
    {
        return $this->moneyTransaction->getCreditIn($brokerID);
    }
    public function getCreditOut(int $brokerID):float
    {
        return $this->moneyTransaction->getCreditOut($brokerID);
    }
    
    public function getBonusIn(int $brokerID):float
    {
        return $this->moneyTransaction->getBonusIn($brokerID);
    }
    public function getBonusOut(int $brokerID):float
    {
        return $this->moneyTransaction->getBonusOut($brokerID);
    }
    public function getPendingWithdrawal(int $brokerId):float
    {
        return $this->moneyTransaction->getPendingWithdrawal($brokerId);
    }
    


    
    
    
}
<?php

namespace App\Http\Services\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\OrderRepositoryInterface;
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class OrderService implements OrderServiceInterface {

    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function getByParams(array $params): ?Collection{
        $results = $this->orderRepository->getByParams($params);
        return $$results;
    }
    public function create(array $data): Collection {
        return $this->orderRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->orderRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->orderRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->orderRepository::insert($data);
    }

    public function deleteByParams(array $params): int {
        return $this->orderRepository->deleteByIDs($Ids);
    }
    
    
}
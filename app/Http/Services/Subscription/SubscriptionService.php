<?php

namespace App\Http\Services\Subscription;

//Interfaces
use App\Http\Repositories\Subscription\Interfaces\SubscriptionRepositoryInterface;
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

class SubscriptionService implements SubscriptionServiceInterface {

    protected $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository) {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function getAll(): Collection{
        $results = $this->subscriptionRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->subscriptionRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->subscriptionRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->subscriptionRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->subscriptionRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->subscriptionRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->subscriptionRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->subscriptionRepository->deleteByIDs($Ids);
    }
    
    
}
<?php
namespace App\Http\Services\Subscription\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionServiceInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
     public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function updateByFilters(array $params, array $data): int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
     public function checkActiveSubscription():void;
}
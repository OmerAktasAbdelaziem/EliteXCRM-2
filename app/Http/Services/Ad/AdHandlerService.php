<?php

namespace App\Http\Services\Ad;

use App\Http\Repositories\Ad\Interfaces\AdHandlerRepositoryInterface;
use App\Http\Services\Ad\Interfaces\AdHandlerServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class AdHandlerService implements AdHandlerServiceInterface
{
    protected AdHandlerRepositoryInterface $adRepository;

    public function __construct(AdHandlerRepositoryInterface $adRepository) {
        $this->adRepository = $adRepository;
    }

    public function getAll(): Collection
    {
        $results = $this->adRepository->getAll();
        return $results;
    }

    public function getById(int $id): Collection
    {
        $results = $this->adRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $results = $this->adRepository->getByFilters($params, $with);
        return $results;
    }

    public function create(array $data): Collection
    {
        return $this->adRepository->create($data);
    }

    public function update(int $id, array $data): int
    {
        return $this->adRepository->update($id, $data);
    }

    public function updateBulk(array $ids, array $data): int
    {
        return $this->adRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool
    {
        return $this->adRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int
    {
        return $this->adRepository->deleteByParams($params);
    }

}

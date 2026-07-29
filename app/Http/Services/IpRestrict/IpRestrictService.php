<?php

namespace App\Http\Services\IpRestrict;

use App\Http\Repositories\IpExemption\Interfaces\IpExemptionRepositoryInterface;
use App\Http\Repositories\IpRestrict\Interfaces\IpRestrictRepositoryInterface;
use App\Http\Services\IpRestrict\Interfaces\IpRestrictServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class IpRestrictService implements IpRestrictServiceInterface
{
    protected IpRestrictRepositoryInterface $ipRestrictRepository;
    protected IpExemptionRepositoryInterface $ipExemptionRepository;

    public function __construct(
        IpRestrictRepositoryInterface $ipRestrictRepository,
        IpExemptionRepositoryInterface $ipExemptionRepository,
    ) {
        $this->ipRestrictRepository = $ipRestrictRepository;
        $this->ipExemptionRepository = $ipExemptionRepository;
    }

    public function getAll(): Collection
    {
        $results = $this->ipRestrictRepository->getAll();
        return $results;
    }

    public function getById(int $id): Collection
    {
        $results = $this->ipRestrictRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $results = $this->ipRestrictRepository->getByFilters($params, $with);
        return $results;
    }

    public function getCurrentIps(int $pipelineId): Collection
    {
        return $this->ipRestrictRepository->getByPipelineId($pipelineId);
    }

    public function getExemptedUsers(int $pipelineId): array
    {
        return $this->ipExemptionRepository->getExemptedUserIdsByPipeline($pipelineId);
    }

    public function updatePipelineConfigurations(int $pipelineId, array $ips, array $userIds): void
    {
        $this->ipRestrictRepository->syncPipelineIps($pipelineId, $ips);
        $this->ipExemptionRepository->syncPipelineExemptions($pipelineId, $userIds);
    }

}

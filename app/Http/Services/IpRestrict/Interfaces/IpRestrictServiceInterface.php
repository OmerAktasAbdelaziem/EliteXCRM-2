<?php

namespace App\Http\Services\IpRestrict\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IpRestrictServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
    public function getCurrentIps(int $pipelineId): Collection;
    public function getExemptedUsers(int $pipelineId): array;
    public function updatePipelineConfigurations(int $pipelineId, array $ips, array $userIds): void;
}

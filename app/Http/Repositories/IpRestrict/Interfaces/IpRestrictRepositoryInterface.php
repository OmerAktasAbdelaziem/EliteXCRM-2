<?php

namespace App\Http\Repositories\IpRestrict\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IpRestrictRepositoryInterface
{
  public function getAll(): Collection;
  public function getById(int $id): Collection;
  public function getByFilters(array $params, array $with = []): Collection;
  public function getByPipelineId(int $pipelineId): Collection;
  public function syncPipelineIps(int $pipelineId, array $ipAddresses): void;

}

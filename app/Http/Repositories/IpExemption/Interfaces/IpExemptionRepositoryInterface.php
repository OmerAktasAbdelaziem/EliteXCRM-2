<?php

namespace App\Http\Repositories\IpExemption\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IpExemptionRepositoryInterface
{
  public function getAll(): Collection;
  public function getById(int $id): Collection;
  public function getByFilters(array $params, array $with = []): Collection;
  public function getExemptedUserIdsByPipeline(int $pipelineId): array;
  public function syncPipelineExemptions(int $pipelineId, array $userIds): void;

}

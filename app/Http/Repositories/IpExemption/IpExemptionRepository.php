<?php

namespace App\Http\Repositories\IpExemption;

use App\Http\Repositories\IpExemption\Interfaces\IpExemptionRepositoryInterface;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Models\IpExemption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class IpExemptionRepository implements IpExemptionRepositoryInterface
{

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getAll(): Collection
    {
        return IpExemption::all();
    }


    public function getById(int $id): Collection
    {
        $item = IpExemption::where('id', $id)->get();
        return $item;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $query = IpExemption::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $filteredQuery = $this->filterService->applyFilters($query, $params);

        return $filteredQuery->get();
    }

    public function getExemptedUserIdsByPipeline(int $pipelineId): array
    {
        return IpExemption::where('pipeline_id', $pipelineId)->pluck('user_id')->toArray();
    }

    public function syncPipelineExemptions(int $pipelineId, array $userIds): void
    {
        // Atomically replace user exemption profiles
        DB::transaction(function () use ($pipelineId, $userIds) {
            IpExemption::where('pipeline_id', $pipelineId)->delete();

            $formatted = array_map(fn($userId) => [
                'pipeline_id' => $pipelineId,
                'user_id'     => (int)$userId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ], array_filter(array_unique($userIds)));

            if (!empty($formatted)) {
                IpExemption::insert($formatted);
            }
        });
    }
}

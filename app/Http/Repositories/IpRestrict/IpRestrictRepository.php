<?php

namespace App\Http\Repositories\IpRestrict;

use App\Http\Repositories\IpRestrict\Interfaces\IpRestrictRepositoryInterface;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Models\IpRestriction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class IpRestrictRepository implements IpRestrictRepositoryInterface
{

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getAll(): Collection
    {
        return IpRestriction::all();
    }


    public function getById(int $id): Collection
    {
        $item = IpRestriction::where('id', $id)->get();
        return $item;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $query = IpRestriction::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $filteredQuery = $this->filterService->applyFilters($query, $params);

        return $filteredQuery->get();
    }

    public function getByPipelineId(int $pipelineId): Collection
    {
        return IpRestriction::where('pipeline_id', $pipelineId)->get();
    }

    public function syncPipelineIps(int $pipelineId, array $ipAddresses): void
    {
        // Atomically replace pipeline IP values
        DB::transaction(function () use ($pipelineId, $ipAddresses) {
            IpRestriction::where('pipeline_id', $pipelineId)->delete();

            $formatted = array_map(fn($ip) => [
                'pipeline_id' => $pipelineId,
                'ip_address'  => trim($ip),
                'created_at'  => now(),
                'updated_at'  => now(),
            ], array_filter(array_unique($ipAddresses)));

            if (!empty($formatted)) {
                IpRestriction::insert($formatted);
            }
        });
    }
}

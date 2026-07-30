<?php

namespace App\Http\Repositories\DefaultStatus;

use App\Http\Repositories\DefaultStatus\Interfaces\DefaultStatusRepositoryInterface;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Models\DefaultStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DefaultStatusRepository implements DefaultStatusRepositoryInterface
{

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getAll(): Collection
    {
        return DefaultStatus::all();
    }


    public function getById(int $id): Collection
    {
        $item = DefaultStatus::where('id', $id)->get();
        return $item;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $query = DefaultStatus::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $filteredQuery = $this->filterService->applyFilters($query, $params);

        return $filteredQuery->get();
    }

    public function create(array $data): Collection
    {
        $defaultStatus = DefaultStatus::create($data);

        return new Collection([$defaultStatus]);
    }

    public function update(int $id, array $data): int
    {
        $defaultStatus = DefaultStatus::findOrFail($id);

        $defaultStatus->update($data);

        return true;
    }

    public function deleteByParams(array $params): int
    {
        return DB::transaction(function () use ($params) {

            if (empty($params)) {
                return 0;
            }

            $query = DefaultStatus::where(function ($query) use ($params) {
                foreach ($params as $key => $value) {
                    if (is_array($value)) {
                        $query->whereIn($key, $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            });

            $ids = $query->pluck('id');

            if ($ids->isEmpty()) {
                return 0;
            }

            return DefaultStatus::whereIn('id', $ids)->delete();
        });
    }
}

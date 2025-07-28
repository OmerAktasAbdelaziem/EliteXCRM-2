<?php

namespace App\Http\Repositories\Asset;

//Interfaces
use App\Http\Repositories\Asset\Interfaces\AssetRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Asset;
//Other
use Illuminate\Database\Eloquent\Collection;

class AssetRepository implements AssetRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Asset::all();
}

public function getById(int $id): Collection {
    $item = Asset::where('id',$id)->get();
    return $item;
}
    
    
    public function getByFilters(array $params,array $with = []): Collection {
        
        
        $query = Asset::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }

    public function create(array $data): Collection {
        $result = Asset::create($data);
        return new Collection([$result]);
    }

    public function update(int $id, array $data): int {
        return $result = Asset::where('id', $id)->update($data);
    }

    public function updateBulk(array $ids, array $data): int {
        return $result = Asset::whereIn('id', $ids)->update($data);
    }

    public function createBulk(array $data): bool {
        return Asset::insert($data);
    }

    public function deleteByParams(array $params): int {
        return Asset::where(function ($query) use ($params) {
                    foreach ($params as $key => $value) {
                        if (is_array($value)) {
                            $query->whereIn($key, $value);
                        } else {
                            $query->where($key, $value);
                        }
                    }
                })->delete();
    }
}

<?php

namespace App\Http\Repositories\Wallet;

use App\Http\Repositories\Wallet\Interfaces\WalletRepositoryInterface;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WalletRepository implements WalletRepositoryInterface
{

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getAll(): Collection
    {
        return Wallet::all();
    }


    public function getById(int $id): Collection
    {
        $item = Wallet::where('id', $id)->get();
        return $item;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $query = Wallet::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $filteredQuery = $this->filterService->applyFilters($query, $params);

        return $filteredQuery->get();
    }

    public function create(array $data): Collection
    {
        $wallet = Wallet::create($data);

        return new Collection([$wallet]);
    }

    public function update(int $id, array $data): int
    {
        $wallet = Wallet::findOrFail($id);

        $wallet->update($data);

        return true;
    }

    public function deleteByParams(array $params): int
    {
        return DB::transaction(function () use ($params) {

            if (empty($params)) {
                return 0;
            }

            $query = Wallet::where(function ($query) use ($params) {
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

            return Wallet::whereIn('id', $ids)->delete();
        });
    }
}

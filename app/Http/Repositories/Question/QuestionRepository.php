<?php

namespace App\Http\Repositories\Question;

use App\Http\Repositories\Question\Interfaces\QuestionRepositoryInterface;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Models\ClientQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class QuestionRepository implements QuestionRepositoryInterface
{

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getAll(): Collection
    {
        return ClientQuestion::all();
    }


    public function getById(int $id): Collection
    {
        $item = ClientQuestion::where('id', $id)->get();
        return $item;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $query = ClientQuestion::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $filteredQuery = $this->filterService->applyFilters($query, $params);

        return $filteredQuery->get();
    }

    public function create(array $data): Collection
    {
        $question = ClientQuestion::create($data);

        return new Collection([$question]);
    }

    public function update(int $id, array $data): int
    {
        $question = ClientQuestion::findOrFail($id);

        $question->update($data);

        return true;
    }

    public function deleteByParams(array $params): int
    {
        return DB::transaction(function () use ($params) {

            if (empty($params)) {
                return 0;
            }

            $query = ClientQuestion::where(function ($query) use ($params) {
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

            return ClientQuestion::whereIn('id', $ids)->delete();
        });
    }
}

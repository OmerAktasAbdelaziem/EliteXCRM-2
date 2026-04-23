<?php

namespace App\Http\Repositories\Ad;

use App\Http\Repositories\Ad\Interfaces\AdHandlerRepositoryInterface;
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
use App\Models\AdHandler;
use App\Models\AdHandlerField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AdHandlerRepository implements AdHandlerRepositoryInterface
{

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    public function getAll(): Collection
    {
        return AdHandler::all();
    }


    public function getById(int $id): Collection
    {
        $item = AdHandler::where('id', $id)->get();
        return $item;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $query = AdHandler::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $filteredQuery = $this->filterService->applyFilters($query, $params);

        return $filteredQuery->get();
    }

    public function create(array $data): Collection
    {
        $result = DB::transaction(function () use ($data) {
            $fields = $data['fields'] ?? [];
            unset($data['fields']);

            $adHandler = AdHandler::create($data);

            if (!empty($fields)) {
                $formattedFields = [];

                foreach ($fields as $sheetField => $crmField) {
                    if(!$crmField) continue;
                    $formattedFields[] = [
                        'crm_field' => $crmField,
                        'sheet_field' => $sheetField,
                    ];
                }

                $adHandler->fields()->createMany($formattedFields);
            }

            return $adHandler;
        });

        return new Collection([$result]);
    }

    public function update(int $id, array $data): int
    {
        return DB::transaction(function () use ($id, $data) {
            $fields = $data['fields'] ?? [];
            unset($data['fields']);

            $adHandler = AdHandler::findOrFail($id);

            $adHandler->update($data);

            $adHandler->fields()->delete();

            if (!empty($fields)) {
                $formattedFields = [];

                foreach ($fields as $sheetField => $crmField) {
                    if(!$crmField) continue;
                    $formattedFields[] = [
                        'crm_field' => $crmField,
                        'sheet_field' => $sheetField,
                    ];
                }

                $adHandler->fields()->createMany($formattedFields);
            }

            return 1;
        });
    }

    public function updateBulk(array $ids, array $data): int
    {
        return DB::transaction(function () use ($ids, $data) {
            $fields = $data['fields'] ?? [];
            unset($data['fields']);

            $adHandlers = AdHandler::whereIn('id', $ids)->get();

            foreach ($adHandlers as $adHandler) {
                $adHandler->update($data);

                $adHandler->fields()->delete();

                if (!empty($fields)) {
                    $formattedFields = [];

                    foreach ($fields as $sheetField => $crmField) {
                        if(!$crmField) continue;
                        $formattedFields[] = [
                            'crm_field' => $crmField,
                            'sheet_field' => $sheetField,
                        ];
                    }

                    $adHandler->fields()->createMany($formattedFields);
                }
            }

            return count($adHandlers);
        });
    }

    public function createBulk(array $data): bool
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                $fields = $item['fields'] ?? [];
                unset($item['fields']);

                $adHandler = AdHandler::create($item);

                if (!empty($fields)) {
                    $formattedFields = [];

                    foreach ($fields as $sheetField => $crmField) {
                        if(!$crmField) continue;
                        $formattedFields[] = [
                            'crm_field' => $crmField,
                            'sheet_field' => $sheetField,
                        ];
                    }

                    $adHandler->fields()->createMany($formattedFields);
                }
            }
        });

        return true;
    }

    public function deleteByParams(array $params): int
    {
        return DB::transaction(function () use ($params) {

            if (empty($params)) {
                return 0;
            }

            $query = AdHandler::where(function ($query) use ($params) {
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

            AdHandlerField::whereIn('ad_handler_id', $ids)->delete();

            return AdHandler::whereIn('id', $ids)->delete();
        });
    }
}

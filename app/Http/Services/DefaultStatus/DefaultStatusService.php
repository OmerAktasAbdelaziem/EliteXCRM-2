<?php

namespace App\Http\Services\DefaultStatus;

use App\Http\Repositories\DefaultStatus\Interfaces\DefaultStatusRepositoryInterface;
use App\Http\Services\DefaultStatus\Interfaces\DefaultStatusServiceInterface;
use App\Models\Pipeline;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DefaultStatusService implements DefaultStatusServiceInterface
{
    protected DefaultStatusRepositoryInterface $defaultStatusRepository;

    public function __construct(DefaultStatusRepositoryInterface $defaultStatusRepository) {
        $this->defaultStatusRepository = $defaultStatusRepository;
    }

    public function getAll(): Collection
    {
        $results = $this->defaultStatusRepository->getAll();
        return $results;
    }

    public function getById(int $id): Collection
    {
        $results = $this->defaultStatusRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $results = $this->defaultStatusRepository->getByFilters($params, $with);
        return $results;
    }

    public function create(array $data): Collection
    {
        $newDefaultStatus = $this->defaultStatusRepository->create($data);

        // add this new status to all piplines 
        DB::insert("
            INSERT INTO statuses (name, pipeline_id, status_key, is_default, part_ids, created_at, updated_at)
            SELECT ?, id, ?, 1, '[]', NOW(), NOW()
            FROM pipelines
        ", [$newDefaultStatus[0]->name, $newDefaultStatus[0]->status_key]);

        return $newDefaultStatus;
    }

    public function update(int $id, array $data): int
    {
        $oldStatusKey = $this->defaultStatusRepository->getById($id)->first()->status_key;

        $done =  $this->defaultStatusRepository->update($id, $data);

        // update this status in all piplines using status_key
        if ($done) {
            $defaultStatus = $this->defaultStatusRepository->getById($id)->first();

            DB::table('statuses')
            ->where('status_key', $oldStatusKey)
            ->where('is_default', 1)
            ->update([
                'name' => $defaultStatus->name,
                'status_key' => $defaultStatus->status_key,
                'updated_at' => now(),
            ]);
        }

        return $done;
    }

    public function deleteByParams(array $params): int
    {
        return $this->defaultStatusRepository->deleteByParams($params);
    }

}

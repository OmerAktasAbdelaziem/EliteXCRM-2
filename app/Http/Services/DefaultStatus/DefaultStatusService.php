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
        // 1. Update existing statuses for this key to become default
        DB::update("
            UPDATE statuses 
            SET is_default = 1, name = ?, updated_at = NOW() 
            WHERE status_key = ?
        ", [$newDefaultStatus[0]->name, $newDefaultStatus[0]->status_key]);

        // 2. Insert ONLY into pipelines that do not have this status_key yet
        DB::insert("
            INSERT INTO statuses (name, pipeline_id, status_key, is_default, part_ids, created_at, updated_at)
            SELECT ?, p.id, ?, 1, '[]', NOW(), NOW()
            FROM pipelines p
            LEFT JOIN statuses s ON s.pipeline_id = p.id AND s.status_key = ?
            WHERE s.id IS NULL
        ", [$newDefaultStatus[0]->name, $newDefaultStatus[0]->status_key, $newDefaultStatus[0]->status_key]);

        return $newDefaultStatus;
    }

    public function update(int $id, array $data): int
    {
        $oldStatusKey = $this->defaultStatusRepository->getById($id)->first()->status_key;

        $done =  $this->defaultStatusRepository->update($id, $data);

        // update this status in all piplines using status_key
        if ($done) {
            $defaultStatus = $this->defaultStatusRepository->getById($id)->first();

            // 1. Update existing statuses for the new key that are not default to become Deprecated
            DB::update("
                UPDATE statuses 
                SET name = CONCAT(name, ' Deprecated'), 
                    status_key = CONCAT(status_key, '_deprecated'), 
                    updated_at = NOW()
                WHERE status_key = ?
                AND is_default = 0
            ", [$defaultStatus->status_key]);

            // 2. Update existing statuses for the old key that are default to the new key and name
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

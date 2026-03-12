<?php
namespace App\Http\Services\Role\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;
use App\Models\Role;


interface RoleServiceInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
     public function create(string $name,int $pipelineId , array $data = []): void;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
     public function safeHasPermission(Role $role, string $permission): bool;
}
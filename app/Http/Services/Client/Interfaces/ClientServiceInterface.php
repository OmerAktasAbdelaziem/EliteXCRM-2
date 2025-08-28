<?php
namespace App\Http\Services\Client\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as supportCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
//Models
use App\Models\User;

interface ClientServiceInterface{
    public function getAll(): Collection;
    public function getById(int $id): Collection;
    public function getByFilters(array $params, array $with = []): Collection;
     public function create(array $data): Collection;
     public function update(int $id,array $data):int;
     public function updateBulk(array $ids,array $data):int;
     public function createBulk(array $data): bool;
     public function deleteByParams(array $params): int;
     public function getTeams(User $user): supportCollection;
     public function getUsers(supportCollection $teams,User $user): supportCollection;
     public function getParts(supportCollection $teams,User $user): supportCollection;
     public function multiEdit(Request $request,User $user): RedirectResponse;
}
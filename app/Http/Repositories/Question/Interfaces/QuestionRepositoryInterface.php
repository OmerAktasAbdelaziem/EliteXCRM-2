<?php

namespace App\Http\Repositories\Question\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface QuestionRepositoryInterface
{
  public function getAll(): Collection;
  public function getById(int $id): Collection;
  public function getByFilters(array $params, array $with = []): Collection;
  public function create(array $data): Collection;
  public function update(int $id, array $data): int;
  public function deleteByParams(array $params): int;
}

<?php

namespace App\Http\Services\Question;

use App\Http\Repositories\Question\Interfaces\QuestionRepositoryInterface;
use App\Http\Services\Question\Interfaces\QuestionServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class QuestionService implements QuestionServiceInterface
{
    protected QuestionRepositoryInterface $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository) {
        $this->questionRepository = $questionRepository;
    }

    public function getAll(): Collection
    {
        $results = $this->questionRepository->getAll();
        return $results;
    }

    public function getById(int $id): Collection
    {
        $results = $this->questionRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $results = $this->questionRepository->getByFilters($params, $with);
        return $results;
    }

    public function create(array $data): Collection
    {
        return $this->questionRepository->create($data);
    }

    public function update(int $id, array $data): int
    {
        return $this->questionRepository->update($id, $data);
    }

    public function deleteByParams(array $params): int
    {
        return $this->questionRepository->deleteByParams($params);
    }

}

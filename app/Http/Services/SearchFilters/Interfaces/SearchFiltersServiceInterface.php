<?php
namespace App\Http\Services\SearchFilters\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface SearchFiltersServiceInterface{
    public function applyFilters(Builder $query, ?array $filters = []): Builder;
    public function applySort(Builder $query): Builder;
    public function getSetSort(?string $by = null, string $dir = 'desc'): array;
    public function getPrev(Builder $query, $current);
    public function getNext(Builder $query, $current);
    public function clear();
}
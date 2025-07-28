<?php
namespace App\Http\Services\Filter\Interfaces;

//Other
use Illuminate\Database\Eloquent\Builder;


interface FilterServiceInterface{
    public function applyFilters(Builder $query, array $filters): Builder;
}
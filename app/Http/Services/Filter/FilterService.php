<?php

namespace App\Http\Services\Filter;

//Interfaces
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Other
use Illuminate\Database\Eloquent\Builder;

class FilterService implements FilterServiceInterface {

    public function __construct() {
        
    }

    /**
     * Applies a set of dynamic filters to the given query builder instance.
     *
     * This method recursively processes an array of filter parameters and applies
     * them to the query builder using appropriate where/orWhere clauses, supporting
     * nested groups of conditions.
     *
     * The filter parameters can include:
     * - Single conditions with field, operator, and value.
     * - Logical OR conditions by setting 'or' => true inside conditions.
     * - Groups of conditions (nested arrays) to be wrapped in parentheses.
     *
     * Supported operators include:
     * - '='  : equals
     * - '!=' or '<>' : not equals
     * - 'like' : LIKE SQL operator
     * - 'notlike' : NOT LIKE SQL operator
     * - 'in' : WHERE IN clause
     * - 'notin' : WHERE NOT IN clause
     * - 'null' : WHERE IS NULL
     * - 'notnull' : WHERE IS NOT NULL
     * - 'between' : WHERE BETWEEN clause
     * - 'order' : ORDER BY clause (note: this does not affect WHERE clauses)
     * - other SQL operators supported by the query builder
     *
     * Example of $params:
     * [
     *    ['group'=>[
     *      ['field'=>'start_date','conditions'=>['>=' => now()]],
     *      ['field'=>'end_date','conditions'=>['<=' => now(),'or'=>true]],
     *    ],
     *    [
     *       'field' => 'status',
     *       'conditions' => ['=' => 'active']
     *    ],
     *    [
     *       'field' => 'type',
     *       'conditions' => ['in' => ['A', 'B'], 'or' => true]
     *    ],
     *    [
     *       'field' => 'category',
     *       'conditions' => ['notIn' => [1, 2, 3]]
     *    ],
     *    [
     *       'field' => 'price',
     *       'conditions' => ['!=' => 100]
     *    ],
     *    [
     *       'field' => 'created_at',
     *       'conditions' => ['order' => 'desc']
     *    ]
     * ]
     *
     * @param array $with Relations to eager load (optional).
     * @return \Illuminate\Support\Collection
     */
    public function applyFilters(Builder $query, array $params): Builder {
        foreach ($params as $param) {
            if (isset($param['group']) && is_array($param['group'])) {
                $isOrGroup = isset($param['conditions']['or']) && $param['conditions']['or'] === true;

                $query->{$isOrGroup ? 'orWhere' : 'where'}(function ($q) use ($param) {
                    $this->applyFilters($q, $param['group']);
                });

                continue;
            }

            if (!isset($param['field'], $param['conditions']) || !is_array($param['conditions'])) {
                continue;
            }

            $field = $param['field'];
            $condition = $param['conditions'];
            $isOr = false;

            if (isset($condition['or']) && $condition['or'] === true) {
                $isOr = true;
                unset($condition['or']);
            }

            foreach ($condition as $operator => $value) {
                $method = $isOr ? 'orWhere' : 'where';

                switch (strtolower($operator)) {
                    case 'in':
                        $query->{$isOr ? 'orWhereIn' : 'whereIn'}($field, $value);
                        break;
                    case 'notin':
                        $query->{$isOr ? 'orWhereNotIn' : 'whereNotIn'}($field, $value);
                        break;
                    case '!=':
                    case '<>':
                        $query->{$method}($field, '!=', $value);
                        break;
                    case '=':
                        $query->{$method}($field, '=', $value);
                        break;
                    case 'like':
                        $query->{$method}($field, 'like', $value);
                        break;
                    case 'notlike':
                        $query->{$method}($field, 'not like', $value);
                        break;
                    case 'null':
                        $query->{$isOr ? 'orWhereNull' : 'whereNull'}($field);
                        break;
                    case 'notnull':
                        $query->{$isOr ? 'orWhereNotNull' : 'whereNotNull'}($field);
                        break;
                    case 'between':
                        $query->{$isOr ? 'orWhereBetween' : 'whereBetween'}($field, $value);
                        break;
                    case 'order':
                        $query->orderBy($field, $value);
                        break;
                    default:
                        $query->{$method}($field, $operator, $value);
                        break;
                }
            }
        }
        return $query;
    }
}

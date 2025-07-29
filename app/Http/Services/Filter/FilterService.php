<?php

namespace App\Http\Services\Filter;

//Interfaces
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;

//Other
use Illuminate\Database\Eloquent\Builder;

class FilterService implements FilterServiceInterface {

  

    public function __construct(){
           
    }
/**
 * Filter query by flexible conditions.
 *
 * @param array $params Array of filter conditions, each item is an associative array with keys:
 *                      - 'field': (string) The database column name to filter on.
 *                      - 'conditions': (array) Associative array of operators and values.  
 *                         Possible operators:
 *                           - '='     => value       (equals)
 *                           - '!=' or '<>' => value (not equals)
 *                           - 'in'    => array      (whereIn)
 *                           - 'notIn' => array      (whereNotIn)
 *                           - 'like'  => string     (LIKE SQL)
 *                           - 'notLike' => string   (NOT LIKE SQL)
 *                           - 'null'  => true       (whereNull)
 *                           - 'notNull' => true     (whereNotNull)
 *                           - 'between' => array    (whereBetween, expects array with two values)
 *                           - 'order' => 'asc'|'desc' (order by this field)
 *                           - 'or'    => true       (optional, applies OR instead of AND for the condition)
 *
 * Example of $params:
 * [
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
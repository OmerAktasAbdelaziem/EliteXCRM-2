<?php
namespace App\Http\Repositories\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PipelineRepositoryInterface;
//Models
use App\Models\Pipeline;
//Other
use Illuminate\Database\Eloquent\Collection;

class PipelineRepository implements PipelineRepositoryInterface {

    
    public function getAll(): Collection
{
    return Pipeline::all();
}
public function getById(int $id): Collection {
    $item = Pipeline::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params): Collection {
        /*
         * Example of params array
          [
          'status' => ['=' => 'active'],
          'type' => ['in' => ['A', 'B']],
          'category' => ['notIn' => [1, 2, 3]],
          'price' => ['!=' => 100],
          ]
         */
        $items = Pipeline::where(function ($query) use ($params) {
        foreach ($params as $field => $condition) {
            if (!is_array($condition)) {
                continue;
            }

            foreach ($condition as $operator => $value) {
                switch (strtolower($operator)) {
                    case 'in':
                        $query->whereIn($field, $value);
                        break;
                    case 'notin':
                        $query->whereNotIn($field, $value);
                        break;
                    case '!=':
                    case '<>':
                        $query->where($field, '!=', $value);
                        break;
                    case '=':
                        $query->where($field, '=', $value);
                        break;
                    case 'like':
                        $query->where($field, 'like', $value);
                        break;
                    case 'notlike':
                        $query->where($field, 'not like', $value);
                        break;
                    case 'null':
                        $query->whereNull($field);
                        break;
                    case 'notnull':
                        $query->whereNotNull($field);
                        break;
                    case 'between':
                        $query->whereBetween($field, $value);
                        break;
                    default:
                        $query->where($field, $operator, $value);
                        break;
                }
            }
        }
         })->get();
        return $items;
    }
  
  public function create(array $data): Collection
    {
        $result = Pipeline::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Pipeline::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Pipeline::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Pipeline::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Pipeline::where(function ($query) use ($params) {
    foreach ($params as $key => $value) {
        if(is_array($value)){
        $query->whereIn($key, $value);    
        }else{
        $query->where($key, $value);
        }
    }
})->delete();
    }
  
  
  
}
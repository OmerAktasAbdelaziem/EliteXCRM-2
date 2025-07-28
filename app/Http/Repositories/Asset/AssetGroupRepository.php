<?php
namespace App\Http\Repositories\Asset;

//Interfaces
use App\Http\Repositories\Asset\Interfaces\AssetGroupRepositoryInterface;
//Models
use App\Models\AssetGroup;
//Other
use Illuminate\Database\Eloquent\Collection;

class AssetGroupRepository implements AssetGroupRepositoryInterface {

    
    public function getAll(): Collection
{
    return AssetGroup::all();
}

public function getById(int $id): Collection {
    $item = AssetGroup::where('id',$id)->get();
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
        $items = AssetGroup::where(function ($query) use ($params) {
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
        $result = AssetGroup::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = AssetGroup::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = AssetGroup::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return AssetGroup::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return AssetGroup::where(function ($query) use ($params) {
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
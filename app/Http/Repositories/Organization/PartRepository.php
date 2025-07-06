<?php
namespace App\Http\Repositories\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PartRepositoryInterface;
//Models
use App\Models\Part;
//Other
use Illuminate\Database\Eloquent\Collection;

class PartRepository implements PartRepositoryInterface {

    public function getByParams(array $params): ?Collection{
      $items = Part::where(function ($query) use ($params) {
    foreach ($params as $key => $value) {
        if(is_array($value)){
        $query->whereIn($key, $value);    
        }else{
        $query->where($key, $value);
        }
    }
})->get();
return $items->isEmpty() ? null : $items;
      
  }
  
  public function create(array $data): Collection
    {
        $result = Part::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Part::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Part::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Part::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Part::where(function ($query) use ($params) {
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
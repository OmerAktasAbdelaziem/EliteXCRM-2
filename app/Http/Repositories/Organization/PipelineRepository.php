<?php
namespace App\Http\Repositories\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PipelineRepositoryInterface;
//Models
use App\Models\Pipeline;
//Other
use Illuminate\Database\Eloquent\Collection;

class PipelineRepository implements PipelineRepositoryInterface {

    public function getByParams(array $params): ?Collection{
      $items = Pipeline::where(function ($query) use ($params) {
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
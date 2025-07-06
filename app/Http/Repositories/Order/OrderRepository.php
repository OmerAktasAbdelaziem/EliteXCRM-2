<?php
namespace App\Http\Repositories\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\OrderRepositoryInterface;
//Models
use App\Models\Order;
//Other
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface {

    public function getByParams(array $params): ?Collection{
      $items = Order::where(function ($query) use ($params) {
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
        $result = Order::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Order::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Order::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Order::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Order::where(function ($query) use ($params) {
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
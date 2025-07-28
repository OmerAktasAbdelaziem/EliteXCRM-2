<?php
namespace App\Http\Repositories\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\OrderRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Order;
//Other
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    public function getAll(): Collection
{
    return Order::all();
}

public function getById(int $id): Collection {
    $item = Order::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection {
        
      
        $query = Order::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
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
  
  public function getClosedOrdersPL(int $brokerId):float
  {
      $closedOrdersPL = Order::where('broker_id', $brokerId)->whereNotNull('closed_at')->sum('pnl');
      return $closedOrdersPL;
  }
  
}
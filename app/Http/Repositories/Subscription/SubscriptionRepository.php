<?php
namespace App\Http\Repositories\Subscription;

//Interfaces
use App\Http\Repositories\Subscription\Interfaces\SubscriptionRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Subscription;
//Other
use Illuminate\Database\Eloquent\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface {

    
    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Subscription::all();
}
    public function getById(int $id): Collection {
    $item = Subscription::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection {
        
        
        $query = Subscription::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    { //print_r($data);die;
        $result = Subscription::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Subscription::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Subscription::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Subscription::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Subscription::where(function ($query) use ($params) {
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
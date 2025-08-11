<?php
namespace App\Http\Repositories\Action;

//Interfaces
use App\Http\Repositories\Action\Interfaces\ActionRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Action;
//Other
use Illuminate\Database\Eloquent\Collection;

class ActionRepository implements ActionRepositoryInterface {

    
    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Action::all();
}
    public function getById(int $id): Collection {
    $item = Action::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection {
        
        
        $query = Action::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
        $result = Action::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Action::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Action::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Action::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Action::where(function ($query) use ($params) {
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
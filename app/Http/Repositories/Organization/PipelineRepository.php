<?php
namespace App\Http\Repositories\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\PipelineRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Pipeline;
//Other
use Illuminate\Database\Eloquent\Collection;

class PipelineRepository implements PipelineRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Pipeline::all();
}
public function getById(int $id): Collection {
    $item = Pipeline::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection{
        
        
        $query = Pipeline::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();

    
 
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
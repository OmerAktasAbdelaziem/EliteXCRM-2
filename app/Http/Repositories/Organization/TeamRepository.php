<?php
namespace App\Http\Repositories\Organization;

//Interfaces
use App\Http\Repositories\Organization\Interfaces\TeamRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Team;
//Other
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Team::all();
}
public function getById(int $id): Collection {
    $item = Team::where('id',$id)->get();
    return $item;
}



    public function getByFilters(array $params, array $with = []): Collection{
        
        
        $query = Team::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
        $result = Team::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Team::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Team::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Team::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Team::where(function ($query) use ($params) {
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
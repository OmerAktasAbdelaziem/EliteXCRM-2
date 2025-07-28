<?php
namespace App\Http\Repositories\Role;

//Interfaces
use App\Http\Repositories\Role\Interfaces\RoleRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Role;
//Other
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface {

    
    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Role::all();
}
    public function getById(int $id): Collection {
    $item = Role::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection {
        
        
        $query = Role::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
        $result = Role::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Role::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Role::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Role::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Role::where(function ($query) use ($params) {
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
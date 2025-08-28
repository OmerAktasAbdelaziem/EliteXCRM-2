<?php
namespace App\Http\Repositories\Role;

//Interfaces
use App\Http\Repositories\Role\Interfaces\PermissionRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\Permission;
//Other
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository implements PermissionRepositoryInterface {

    
    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return Permission::all();
}
    public function getById(int $id): Collection {
    $item = Permission::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection {
        
        
        $query = Permission::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);
//dd($filteredQuery->getBindings());
    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
        $result = Permission::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = Permission::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = Permission::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return Permission::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return Permission::where(function ($query) use ($params) {
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
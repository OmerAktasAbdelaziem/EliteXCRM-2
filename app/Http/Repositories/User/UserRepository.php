<?php
namespace App\Http\Repositories\User;

//Interfaces
use App\Http\Repositories\User\Interfaces\UserRepositoryInterface;

//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;

//Models
use App\Models\User;
//Other
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return User::all();
}
    public function getById(int $id): Collection {
    $item = User::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params, array $with = []): Collection {
        
        $query = User::query();

        
    if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
        $result = User::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = User::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = User::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return User::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return User::where(function ($query) use ($params) {
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
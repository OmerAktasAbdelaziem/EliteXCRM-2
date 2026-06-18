<?php
namespace App\Http\Repositories\Asset;

//Interfaces
use App\Http\Repositories\Asset\Interfaces\AssetGroupRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\AssetGroup;
//Other
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Support\Facades\DB;

class AssetGroupRepository implements AssetGroupRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    public function getAll(): Collection
{
    return AssetGroup::all();
}

public function cloneAssetGroup(int $newPipelineId): ?AssetGroup
{
    $item = AssetGroup::find(1);

    if (!$item) {
        return null;
    }

    $newItem = AssetGroup::create([
        'asset_ids'   => $item->assetAssignments->pluck('asset'),
        'name'        => $item->name,
        'pipeline_id' => $newPipelineId,
    ]);

    DB::table('asset_group_assignments')->insertUsing(
        [
            'asset',
            'asset_group',
            'size',
            'leverage',
            'bid_spread',
            'ask_spread',
            'buy_commission',
            'sell_commission',
            'is_percentage'
        ],
        DB::table('asset_group_assignments')
            ->selectRaw('
                asset,
                '.$newItem->id.' as asset_group,
                size,
                leverage,
                bid_spread,
                ask_spread,
                buy_commission,
                sell_commission,
                is_percentage
            ')
            ->where('asset_group', 1)
    );


    return $newItem;
}


public function getById(int $id): Collection {
    $item = AssetGroup::where('id',$id)->get();
    return $item;
}
    public function getByFilters(array $params,array $with = []): Collection {
        
        
        $query = AssetGroup::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
        $result = AssetGroup::create($data);
        return new Collection([$result]);
    }
    public function update(int $id,array $data): int
    {
        return $result = AssetGroup::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = AssetGroup::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return AssetGroup::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return AssetGroup::where(function ($query) use ($params) {
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
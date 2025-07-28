<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetGroupAssignment extends Model
{
   
    public $timestamps = false;

   
    protected $table = 'asset_group_assignments';

   
    protected $fillable = [
        'asset',
        'asset_group',
        'size',
        'leverage',
        'bid_spread',
        'ask_spread',
        'buy_commission',
        'sell_commission',
        'is_percentage',
    ];

    
    public function relatedAsset()
    {
        return $this->belongsTo(Asset::class, 'asset');
    }

   
    public function relatedAssetGroup()
    {
        return $this->belongsTo(AssetGroup::class, 'asset_group');
    }
}
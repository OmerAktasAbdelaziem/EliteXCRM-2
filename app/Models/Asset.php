<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_commission',
        'buy_commission',
        'is_percentage',
        'ask_spread',
        'bid_spread',
        'is_active',
        'bid_price',
        'ask_price',
        'currency',
        'leverage',
        'category',
        'last_bid',
        'last_ask',
        'symbol',
        'type',
        'name',
        'size',
        'img',
    ];

    protected $casts = [
        'sell_commission' => 'array',
        'buy_commission'  => 'array',
        'is_percentage'   => 'array',
        'ask_spread'      => 'array',
        'bid_spread'      => 'array',
        'leverage'        => 'array',
        'size'            => 'array',
    ];
    
    public function groupAssignments()
{
    return $this->hasMany(AssetGroupAssignment::class, 'asset');
}
//$groups = AssetGroup::with(['assetAssignments.asset'])->get();
}

class AssetFromDb1 extends Model
{
    protected $connection = 'ring_trade_db';
    protected $table = 'assets';
}

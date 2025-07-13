<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'pipeline_id',
        'asset_ids',
        'name',
    ];

    protected $casts = [
        'asset_ids' => 'array',
    ];
    
    public function assetAssignments()
{
    return $this->hasMany(AssetGroupAssignment::class, 'asset_group');
}
//$groups = AssetGroup::with(['assetAssignments.asset'])->get();
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpRestriction extends Model
{
    protected $table = 'ip_restrictions';
    
    protected $fillable = ['pipeline_id', 'ip_address'];

    /**
     * Get the pipeline that owns this IP restriction.
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }
}
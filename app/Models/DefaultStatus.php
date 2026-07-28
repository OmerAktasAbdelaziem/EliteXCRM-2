<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status_key',
    ];

    protected $table = 'pipeline_default_statuses';
    
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // if (empty($model->status_key) && !empty($model->name)) {
                $model->status_key = strtolower(str_replace(' ', '_', trim($model->name)));
            // }
        });
    }
}
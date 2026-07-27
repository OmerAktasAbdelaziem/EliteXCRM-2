<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'pipeline_id', 'part_ids', 'status_key', 'is_default'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (Auth::check()) {
                $model->pipeline_id = Auth::user()->pipeline_id;
            }

            // if (empty($model->status_key) && !empty($model->name)) {
                $model->status_key = strtolower(str_replace(' ', '_', trim($model->name)));
            // }
        });
    }

    public function newEloquentBuilder($query)
    {
        $builder = parent::newEloquentBuilder($query);

        if (Auth::check()) {
            $builder->where('pipeline_id', Auth::user()->pipeline_id);
        }

        return $builder;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'status_teams');
    }

}

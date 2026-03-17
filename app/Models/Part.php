<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['leader_id', 'name', 'role_id', 'pipeline_id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (Auth::check()) {
                $model->pipeline_id = Auth::user()->pipeline_id;
            }
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
        return $this->hasMany(Team::class,'part_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function role()
    {
        return $this->belongsTo(OldRole::class, 'role_id');
    }
}

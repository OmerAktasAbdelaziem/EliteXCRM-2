<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OldRole extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = ['name', 'options', 'pipeline_id'];

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

    public function users()
    {
        return $this->hasMany(User::class,'role_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class,'role_id');
    }
    
    public function parts()
    {
        return $this->hasMany(Part::class,'role_id');
    }
}

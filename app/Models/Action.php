<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Action extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'user_id', 'text', 'pipeline_id'];

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
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

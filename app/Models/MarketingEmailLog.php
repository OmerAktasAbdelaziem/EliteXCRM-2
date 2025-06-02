<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MarketingEmailLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'client_id', 'pipeline_id', 'template_id', 'sender_email_id', 'text'];

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
    
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function sender_email()
    {
        return $this->belongsTo(SenderEmail::class, 'sender_email_id');
    }
}

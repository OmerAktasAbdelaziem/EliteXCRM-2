<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_country',
        'beneficiary_address',
        'aba_routing_number',
        'beneficiary_name',
        'account_number',
        'pipeline_id',
        'swift_code',
        'is_active',
        'currency',
        'address',
        'country',
        'type',
        'name',
        'iban',
        'bic',
    ];

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

    public function moneyTrx()
    {
        return $this->hasMany(MoneyTrx::class);
    }
}

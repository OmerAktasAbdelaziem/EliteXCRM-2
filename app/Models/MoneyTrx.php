<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTrx extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_details',
        'created_at',
        'broker_id',
        'is_admin',
        'bank_id',
        'comment',
        'receipt',
        'amount',
        'status',
        'type',
        'usdt',
    ];

    protected $casts = [
        'bank_details' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'broker_id','broker_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function details()
{
    return $this->hasMany(MoneyTrxDetail::class, 'money_trx', 'id');
}
}


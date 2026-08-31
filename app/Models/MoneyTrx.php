<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'updated',
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


    /**
     * Get all extra field answers for the transaction.
     */
    public function extraFieldAnswers(): HasMany
    {
        return $this->hasMany(MoneyTrxesExtraFieldAnswer::class, 'money_trxes_id', 'id');
    }
}


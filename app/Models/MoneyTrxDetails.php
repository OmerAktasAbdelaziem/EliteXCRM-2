<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTrxDetails extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'money_trxes_details';
    protected $fillable = [
        'money_trx',
        'type',
        'amount',
    ];

  
}

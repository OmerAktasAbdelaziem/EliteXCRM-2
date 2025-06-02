<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArkAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type',
        'account_id',
        'client_id',
        'username',
        'password',
        'user_id',
        'broker',
        'amount',
        'id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

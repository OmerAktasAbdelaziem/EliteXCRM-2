<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_url',
        'client_type',
        'from_email',
        'client_id',
        'wallet_id',
        'to_email',
        'logo_url',
        'username',
        'password',
        'user_id',
        'company',
        'amount',
        'type',
        'name',
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

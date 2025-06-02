<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'status',
        'path',
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}

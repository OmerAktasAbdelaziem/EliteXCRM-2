<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'verification_level',
        'times_to_try',
        'user_id',
        'email',
        'type',
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

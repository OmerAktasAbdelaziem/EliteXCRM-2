<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartUser extends Model
{
    use HasFactory;

    protected $connection = 'smart_mysql';
    protected $table = 'wp_users';
    protected $primaryKey = 'ID';

    protected $fillable = ['amount', 'bonus', 'user_email', 'user_pass', 'user_registered', 'wallet_id', 'user_login', 'user_nicename', 'display_name'];

    public $timestamps = false;

}

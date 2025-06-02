<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartUserMeta extends Model
{
    use HasFactory;

    protected $connection = 'smart_mysql';
    protected $table = 'wp_usermeta';
    protected $primaryKey = 'umeta_id';

    protected $fillable = ['meta_key','meta_value','user_id'];

    public $timestamps = false;
}

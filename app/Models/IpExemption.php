<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpExemption extends Model
{
    protected $table = 'ip_exemptions';

    protected $fillable = ['pipeline_id', 'user_id'];
}

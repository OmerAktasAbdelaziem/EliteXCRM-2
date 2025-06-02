<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function pipelines()
    {
        return $this->hasMany(Pipeline::class);
    }
}

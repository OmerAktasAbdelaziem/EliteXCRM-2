<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdHandlerField extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_handler_id',
        'crm_field',
        'sheet_field',
    ];

    public function adHandler()
    {
        return $this->belongsTo(AdHandler::class);
    }
}
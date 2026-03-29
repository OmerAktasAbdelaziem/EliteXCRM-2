<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'type',
        'original_name',
        'file_path',
        'path',
        'status',
        'file_size',
        'mime_type',
        'uploaded_at'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}

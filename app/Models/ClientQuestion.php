<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'pipeline_id',
        'is_text'
    ];

    protected $casts = [
        'is_text' => 'boolean',
    ];

    public function answers()
    {
        return $this->hasMany(ClientQuestionAnswer::class, 'client_question_id');
    }
}

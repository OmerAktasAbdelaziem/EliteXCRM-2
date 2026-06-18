<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientQuestionAnswer extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'client_id',
        'client_question_id',
        'answer'
    ];

    public function question()
    {
        return $this->belongsTo(ClientQuestion::class, 'client_question_id');
    }
}

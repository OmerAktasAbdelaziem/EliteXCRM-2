<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'pipeline',
        'start_date',
        'end_date',
        'parts_count',
        'teams_count',
        'users_count',
        'supscription_type',
    ];

    // Subscription belongs to a Pipeline
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline');
    }
}


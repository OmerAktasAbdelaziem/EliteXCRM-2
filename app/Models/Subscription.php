<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'pipeline',
        'start_date',
        'end_date',
        'active',
        'parts_count',
        'teams_count',
        'users_count',
        'real_accounts',
        'demo_accounts',
        'supscription_type',
    ];

    // Subscription belongs to a Pipeline
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline');
    }
}


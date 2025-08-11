<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'get_clients_from_api',
        'category_id',
        'support_ids',
        'part_limit',
        'user_limit',
        'team_limit',
        'broker_id',
        'co_id',
        'name',
        'usdt',
    ];

    protected $casts = [
        'usdt' => 'array',
    ];

    public function co()
    {
        return $this->belongsTo(User::class, 'co_id');
    }

    public function emailTemplates()
    {
        return $this->hasMany(EmailTemplate::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'pipeline');
    }
}

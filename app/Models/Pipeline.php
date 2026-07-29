<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        'webtrader_url',
        'usdt',
        'webtrader_message_en',
        'webtrader_message_ar',
        'show_webtrader_message_icon',
        'is_main',
    ];

    protected $casts = [
        'usdt' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        // When a new pipeline is created, copy all default statuses to it
        static::created(function ($pipeline) {
            DB::insert("
                INSERT INTO statuses (name, pipeline_id, status_key, is_default, part_ids, created_at, updated_at)
                SELECT name, ?, status_key, 1, '[]', NOW(), NOW()
                FROM pipeline_default_statuses
            ", [$pipeline->id]);
        });
    }

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
        return $this->hasMany(Subscription::class, 'pipeline');
    }

    /**
     * Get all whitelisted IP addresses for this pipeline.
     */
    public function allowedIps(): HasMany
    {
        return $this->hasMany(IpRestriction::class, 'pipeline_id');
    }
    
    /**
     * Get all users exempted from IP rules for this pipeline.
     */
    public function exemptedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ip_exemptions', 'pipeline_id', 'user_id')
                    ->withTimestamps();
    }

}

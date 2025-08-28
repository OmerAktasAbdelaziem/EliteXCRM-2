<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
//use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasRolesWithPipeline;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasRolesWithPipeline;//HasRoles;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'password_changed_at',
        'retention_clients',
        'channel_name',
        'pipeline_id',
        'first_name',
        'last_name',
        'username',
        'password',
        'role_ids',
        'team_id',
        'role_id',
        'gender',
        'email',
        'id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'retention_clients' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (Auth::check()) {
                $model->pipeline_id = Auth::user()->pipeline_id;
            }
        });
    }

    public function assignRoleWithPipeline($roleName, $pipelineId = null)
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $this->roles()->attach($role->id, [
            'model_type' => static::class,
            'pipeline_id' => $pipelineId
        ]);
    }
    
    /*public function hasRoleInPipeline($roleName, $pipelineId)
    {
        return $this->roles()
            ->where('name', $roleName)
            ->wherePivot('pipeline_id', $pipelineId)
            ->exists();
    }*/
    
    /**
     * Check if user has permission in a specific pipeline
     */
    /*public function hasPermissionInPipeline(string $permissionName, $pipelineId = null): bool
    {
        return $this->permissions()
                    ->where('name', $permissionName)
                    ->wherePivot('pipeline_id', $pipelineId)
                    ->exists()
            || $this->rolesInPipeline($pipelineId)
                    ->whereHas('permissions', function ($q) use ($permissionName, $pipelineId) {
                        $q->where('name', $permissionName)
                          ->wherePivot('pipeline_id', $pipelineId);
                    })->exists();
    }*/
    
    public function scopeWithPipeline($query)
    {
        $query->where('pipeline_id', Auth::user()->pipeline_id);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function updatedclients()
    {
        return $this->hasMany(Client::class, 'updated_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function text()
    {
        return $this->hasOne(Text::class);
    }
    
    public function client_comments()
    {
        return $this->hasMany(Client_comment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'user_id');
    }

    public function ledTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    public function ledParts(): HasMany
    {
        return $this->hasMany(Part::class, 'leader_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function role()
    {
        return $this->belongsTo(OldRole::class, 'role_id');
    }

    public function marketing_email_logs()
    {
        return $this->hasMany(MarketingEmailLog::class, 'user_id');
    }

    public function co_pipeline()
    {
        return $this->hasMany(Pipeline::class, 'co_id');
    }
}
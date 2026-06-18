<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_date',
        'last_captured_at',
        'asset_group_id',
        'is_have_invest',
        'is_have_money',
        'password_text',
        'sales_status',
        'is_have_time',
        'last_seen_at',
        'company_name',
        'account_type',
        'first_owner',
        'assigned_at',
        'pipeline_id',
        'is_notified',
        'first_name',
        'deleted_at',
        'ftd_amount',
        'created_by',
        'created_at',
        'renewed_at',
        'ftd_bonus',
        'last_name',
        'broker_id',
        'is_online',
        'how_money',
        'ark_data',
        'password',
        'reg_date',
        'ftd_date',
        'campaign',
        'username',
        'leverage',
        'is_renew',
        'loggedAt',
        'user_id',
        'deleted',
        'country',
        'message',
        'form_id',
        'options',
        'phone1',
        'source',
        'phone2',
        'is_ftd',
        'gender',
        'is_25',
        'email',
        'usdt',
        'age',
        'id',
        'ad',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (Auth::check()) {
                $model->pipeline_id = Auth::user()->pipeline_id;
            }
        });
        
        static::creating(function ($client) {
            if (empty($client->favourite_assets)) {
               /* $client->favourite_assets = json_encode(Asset::whereIn('currency', ['AUD', 'EUR'])
                    ->orWhereIn('symbol', ['GOLD', 'Oil', 'SILVER'])
                    ->pluck('id')
                    ->unique()
                    ->values()
                    ->toArray();)*/
                    $client->favourite_assets = json_encode(
    Asset::whereIn('currency', ['AUD', 'EUR'])
        ->orWhereIn('name', ['GBPAUD', 'Gold', 'Brent crude oil', 'Silver','SPCX'])
        ->pluck('id')
        ->unique()
        ->values()
        ->toArray()
);
            }
        });
    }

    public function newEloquentBuilder($query)
    {
        $builder = parent::newEloquentBuilder($query);

        if (Auth::check()) {
            $builder->where($this->getTable() . '.pipeline_id', Auth::user()->pipeline_id);
        }

        return $builder;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'client_id');
    }

    public function comments()
    {
        return $this->hasMany(Client_comment::class, 'client_id');
    }
    
    public function document()
    {
        return $this->hasOne(ClientDocument::class, 'client_id');
    }

    public function ark_accounts()
    {
        return $this->hasMany(ArkAccount::class, 'client_id');
    }

    public function firstOwner()
    {
        return $this->belongsTo(User::class, 'first_owner');
    }

    public function marketing_email_logs()
    {
        return $this->hasMany(MarketingEmailLog::class, 'client_id');
    }

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function assetGroup()
    {
        return $this->belongsTo(AssetGroup::class,'asset_group_id');
    }

    public function orders()
    {
        return $this->hasMany(Client_comment::class, 'client_id', 'broker_id');
    }
        public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');

    }

    public function getLastSeenOnlineAttribute()
    {
        if (!$this->last_seen_at) {
            return 'Never';
        }
        return \Carbon\Carbon::parse($this->last_seen_at)->diffForHumans();
    }


    public function questionAnswers()
    {
        return $this->hasMany(ClientQuestionAnswer::class, 'client_id');
    }

    public function markOnline()
    {
            // \Log::info("Marking client online", ['client_id' => $this->id]);
        $this->last_seen_at = now();
        $this->save();
    }

}

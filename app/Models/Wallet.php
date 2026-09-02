<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wallet extends Model
{
    protected $fillable = [
        'pipeline_id',
        'type',
        'name_en',
        'name_ar',
        'address',
        'network',
    ];

    /**
     * Get the fields associated with the wallet.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(WalletField::class)->orderBy('order', 'asc');
    }

    /**
     * Get the countries associated with the wallet.
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'wallet_countries', 'wallet_id', 'country_id')
                    ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Country extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'iso_code',
    ];

    /**
     * Get the wallets associated with the country.
     */
    public function wallets(): BelongsToMany
    {
        return $this->belongsToMany(Wallet::class, 'wallet_countries', 'country_id', 'wallet_id')
                    ->withTimestamps();
    }
}

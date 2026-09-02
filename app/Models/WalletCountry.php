<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WalletCountry extends Pivot
{
    protected $table = 'wallet_countries';

    protected $fillable = [
        'wallet_id',
        'country_id',
    ];
}

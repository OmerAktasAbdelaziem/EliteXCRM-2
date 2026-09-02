<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalletField extends Model
{
    protected $fillable = [
        'wallet_id',
        'arabic_field_name',
        'english_field_name',
        'arabic_field_value',
        'english_field_value',
        'order',
    ];

    /**
     * Get the wallet that owns the field.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the answers related to this extra field.
     */
    public function extraFieldAnswers(): HasMany
    {
        return $this->hasMany(MoneyTrxesExtraFieldAnswer::class, 'wallet_field_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoneyTrxesExtraFieldAnswer extends Model
{
    protected $table = 'money_trxes_extra_field_answers';

    protected $fillable = [
        'money_trxes_id',
        'wallet_field_id',
        'field_text',
        'client_answer',
    ];

    /**
     * Get the wallet field that this answer belongs to.
     */
    public function walletField(): BelongsTo
    {
        return $this->belongsTo(WalletField::class, 'wallet_field_id');
    }

    /**
     * Get the transaction that owns this answer.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(MoneyTrx::class, 'money_trxes_id');
    }
}

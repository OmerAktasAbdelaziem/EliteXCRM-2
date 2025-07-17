<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsHistory extends Model
{
	use HasFactory;
	protected $table = 'assets_history';
    protected $fillable = [
        'name',
        'type',
        'category',
        'symbol',
        'currency',
        'bid_price',
        'ask_price',
        'last_bid',
	'last_ask',
    ];
}

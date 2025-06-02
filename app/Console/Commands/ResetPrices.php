<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;

class ResetPrices extends Command
{
    protected $signature = 'reset:prices';
    protected $description = 'Reset all prices';

    public function handle()
    {
        $assets = Asset::get();

        foreach ($assets as $asset) {
            $asset->update([
                'last_bid'  => $asset->bid_price,
                'last_ask'  => $asset->ask_price,
            ]);
        }
    }
}

<?php
/*
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
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class ResetPrices extends Command
{
    protected $signature = 'reset:prices';
    protected $description = 'Reset all prices';

    public function handle()
    {
        
        Asset::query()->update([
            'last_bid'  => DB::raw('bid_price'),
            'last_ask'  => DB::raw('ask_price'),
        ]);
        
        $this->info('Prices reset successfully using a single bulk query.');
    }
}
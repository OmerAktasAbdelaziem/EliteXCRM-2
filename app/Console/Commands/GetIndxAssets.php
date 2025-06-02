<?php

namespace App\Console\Commands;

use App\Models\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetIndxAssets extends Command
{
    protected $signature = 'get:indx-assets';
    protected $description = 'Listen to EOD Historical Data api for real-time Index bid/ask prices';

    public function handle()
    {
        $number = Asset::where('type', 'Indx')->where('is_active',1)->count();
        $Indxes = Asset::where('type', 'Indx')->where('is_active',1)->pluck('symbol')->toArray();

        // Convert array to a comma-separated string
        $symbols = implode(',', $Indxes);
        $spreads = [
            'GDAXI.INDX' => 1.4,
            'FCHI.INDX'  => 1.2,
            'IBEX.INDX'  => 5,
            'GSPC.INDX'  => 1.4,
            'DJI.INDX'   => 1.4,
            'NDX.INDX'   => 1.4,
        ];
        $loop = true;
        while($loop){
            $prices = Http::baseUrl('https://eodhd.com')->get("/api/real-time/{$symbols}?api_token=67f4cea78e4f60.22404437&fmt=json");

            if ($prices->successful()) {
                foreach ($prices->json() as $price) {
                    if ($price['close'] != 'NA') {
                        $asset = Asset::where('symbol', $price['code'])->first();
                        if ($asset) {
                            $asset->update([
                                'bid_price' => $price['close'],
                                'ask_price' => (int)$price['close']+$spreads[$price['code']],
                                'last_bid'  => $asset->bid_price,
                                'last_ask'  => $asset->ask_price,
                            ]);
                        }
                    }
                }
            }
            sleep($number);
        }
    }
}

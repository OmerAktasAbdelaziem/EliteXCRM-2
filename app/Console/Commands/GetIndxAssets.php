<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\AssetsHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
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

            if ($prices->successful() ) {//&& 1 == 2
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
                            //following code will add values to asset_history, which will be used to retrieve data in range of 10 minutes in case of websocket failed,
                        //this is wrong logic but requested by company, and should be handeled in another way someday
                            
                            AssetsHistory::create([
        'name' => $asset->name,
        'type' => $asset->type,
        'category' => $asset->category,
        'symbol' => $asset->symbol,
        'currency' => $asset->currency,
        'bid_price' => $price['close'],
        'ask_price' => (int)$price['close']+$spreads[$price['code']],
        'last_bid' => $asset->bid_price,
        'last_ask' => $asset->ask_price,
        
    ]);
                           
                            //end of adding to assets_history
                        }
                    }
                }
            }else{
                        
                        //this code added to get data from history in case of websocket didn't work, it's wrong logic but requested by Walid
                        $assets = Asset::where('type', 'Indx')->where('is_active',1)->get();
                        foreach($assets as $asset){
                            $assetHistory = AssetsHistory::where('type', 'Indx')
                                    ->where('symbol',$asset->symbol)
                                    ->where('created_at', '>=', Carbon::now()->subMinutes(10))
                                    ->first();
                            if(isset($assetHistory->bid_price) && isset($assetHistory->ask_price)){
                            $asset->update([
                                'bid_price' => $assetHistory->bid_price,
                                    'ask_price' => $assetHistory->ask_price,
                                    'last_bid'  => $asset->bid_price,
                                    'last_ask'  => $asset->ask_price,
                            ]);
                            }
                        }
                        // end of retrieve data from history
                    }
            sleep($number);
        }
    }
}

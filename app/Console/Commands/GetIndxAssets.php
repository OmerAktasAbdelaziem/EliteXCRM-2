<?php
/*
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
                            
                  
                        }
                    }
                }
            }
            sleep($number);
        }
    }
}
*/


namespace App\Console\Commands;

use App\Models\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetIndxAssets extends Command
{
    protected $signature = 'get:indx-assets';
    protected $description = 'Listen to EOD API for real-time Index prices with optimized DB updates';

    protected $assetsCache = [];

    public function handle()
    {
        // 1. جلب الأصول النشطة وتخزينها في الذاكرة
        $assets = Asset::where('type', 'Indx')->where('is_active', 1)->get();
        foreach ($assets as $asset) {
            $this->assetsCache[$asset->symbol] = $asset;
        }

        $symbols = implode(',', array_keys($this->assetsCache));
        $spreads = [
            'GDAXI.INDX' => 1.4, 'FCHI.INDX' => 1.2, 'IBEX.INDX' => 5,
            'GSPC.INDX' => 1.4, 'DJI.INDX' => 1.4, 'NDX.INDX' => 1.4,
        ];

        while (true) {
            $response = Http::timeout(10)->get("https://eodhd.com/api/real-time/{$symbols}?api_token=67f4cea78e4f60.22404437&fmt=json");

            if ($response->successful()) {
                foreach ($response->json() as $price) {
                    $code = $price['code'];
                    $newPrice = $price['close'];

                    if (isset($this->assetsCache[$code]) && $newPrice != 'NA') {
                        $asset = $this->assetsCache[$code];
                        
                        // 2. تحديث الذاكرة وقاعدة البيانات فقط إذا تغير السعر فعلياً
                        if ($asset->bid_price != $newPrice) {
                            $spread = $spreads[$code] ?? 1.0;
                            
                            $asset->update([
                                'bid_price' => $newPrice,
                                'ask_price' => (float)$newPrice + $spread,
                                'last_bid'  => $asset->bid_price,
                                'last_ask'  => $asset->ask_price,
                            ]);

                            // تحديث الذاكرة لتعكس السعر الجديد
                            $asset->bid_price = $newPrice;
                        }
                    }
                }
            }
            
            // 3. تأخير منطقي
            sleep(5); 
        }
    }
}

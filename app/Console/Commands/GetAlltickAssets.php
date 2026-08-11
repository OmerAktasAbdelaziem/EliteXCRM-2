<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use React\EventLoop\Factory;

class GetAlltickAssets extends Command
{
    protected $signature = 'get:alltick-assets';
    protected $description = 'Listen to EOD WebSocket with optimized DB handling';

    // نضع البيانات في الذاكرة لتجنب الاستعلام المتكرر
    protected $assetsCache = [];

    public function handle()
    {
        // جلب الأصول مرة واحدة فقط وتخزينها في مصفوفة
        // $assets = Asset::where('type', 'alltick')->get();
        $assets = Asset::all();

        foreach ($assets as $asset) {
            $this->assetsCache[$asset->symbol] = $asset;
        }

        $wsUrl = "wss://quote.alltick.co/quote-b-ws-api?token=5cec652a95a61e8cd8d380a4b286d282-c-app";
        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop);
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop)
    {
        // 1. تمرير المتغيرات المطلوبة في use
        $connector($wsUrl)->then(function (WebSocket $conn) use ($wsUrl, $connector, $loop) {
            // جلب الرموز للاشتراك وإرسالها

            $symbols = [];
            foreach (array_keys($this->assetsCache) as $symbol) {
                $symbols[] = [
                    'code' => $symbol
                ];
            }

            $subscribeMessage = json_encode([
                "cmd_id" => 22002,
                "seq_id" => 1,
                "trace" => "202607301140",
                "data" => [
                    "symbol_list" => $symbols
                ]
            ]);
            
            $conn->send($subscribeMessage);

            $conn->on('message', function ($data) {
                $response = json_decode($data, true);

                if(!isset($response['data'])) return;

                $response = $response['data'];

                $symbol = $response['code'];
                $bidPrice = $response['bids'][0]['price'];
                $askPrice = $response['asks'][0]['price'];

                // التحديث فقط إذا وجدنا الأصل في الذاكرة
                if (isset($this->assetsCache[$symbol])) {
                    $asset = $this->assetsCache[$symbol];
                    
                    // التحديث فقط إذا تغير السعر فعلياً
                    if ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice) {
                        
                        $asset->update([
                            'bid_price' => $bidPrice,
                            'ask_price' => $askPrice,
                            'last_bid'  => $asset->bid_price, // يأخذ القيمة القديمة من الذاكرة
                            'last_ask'  => $asset->ask_price,
                        ]);

                        // 3. ⚠️ هذا السطر كان مفقوداً وهو الأهم: تحديث الذاكرة لتجنب الـ Loop اللانهائي
                        $asset->bid_price = $bidPrice;
                        $asset->ask_price = $askPrice;
                    }
                }
            });

            // 2. ⚠️ إضافة حدث إعادة الاتصال عند انقطاع السوكيت
            $conn->on('close', function ($code = null, $reason = null) use ($wsUrl, $connector, $loop) {
                sleep(2); // تأخير بسيط قبل المحاولة لتجنب الحظر
                $this->startWebSocket($wsUrl, $connector, $loop);
            });

        }, function ($e) use ($wsUrl, $connector, $loop) {
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop);
        });
    }
}
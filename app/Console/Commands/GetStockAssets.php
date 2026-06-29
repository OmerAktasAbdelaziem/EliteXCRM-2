<?php
/*
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use App\Models\AssetsHistory;
use React\EventLoop\Factory;
use Carbon\Carbon;

class GetStockAssets extends Command
{
    protected $signature = 'get:stock-assets';
    protected $description = 'Listen to EOD Historical Data WebSocket for real-time Stocks bid/ask prices';

    public function handle()
    {
        // Fetch Stock symbols from the database
        $symbols = Asset::where('type', 'Stocks')->orderBy('id', 'asc')->pluck('symbol')->map(fn($s) => strtoupper($s))->toArray();//limit(20)->

        if (empty($symbols)) {
            $this->error('⚠️ No Stocks assets found in the database.');
            return;
        }

        // Format symbols for WebSocket request
        $symbolsString = implode(",", $symbols);
        $subscribeMessage = json_encode([
            "action"  => "subscribe",
            "symbols" => $symbolsString
        ]);

        // WebSocket URL
        $wsUrl = "wss://ws.eodhistoricaldata.com/ws/us-quote?api_token=67f4cea78e4f60.22404437";

        // Create event loop
        $loop = Factory::create();
        $connector = new Connector($loop);

        // Start WebSocket
        $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);

        // Run event loop
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop, $subscribeMessage)
    {
        $connector($wsUrl)->then(
            function (WebSocket $conn) use ($wsUrl, $subscribeMessage, $connector, $loop) {

                // Send subscription message
                $conn->send($subscribeMessage);

                // Listen for incoming messages
                $conn->on('message', function ($data) {
                    $response = json_decode($data, true);
                    
                    //print_r($response); echo '<br>';
                    
                    static $lastProcessed = [];
                    $symbol = strtoupper($response['s']?? null);
                    $now = microtime(true);

//if (isset($lastProcessed[$symbol]) && ($now - $lastProcessed[$symbol]) < 0.5) {
//    return;
//}
$lastProcessed[$symbol] = $now;

                    

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return;
                    }

                    if (isset($response['s'], $response['bp'], $response['ap'])) { // && 1 == 2
                        $symbol = strtoupper($response['s']);
                        $bidPrice = $response['bp'];
                        $askPrice = $response['ap'];

                        // Update database with real-time bid/ask prices
                        $asset = Asset::where('symbol', $symbol)->first();
                        if ($asset && ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice)) {
                            $asset->update([
                                'bid_price' => $bidPrice,
                                'ask_price' => $askPrice,
                                'last_bid'  => $asset->bid_price,
                                'last_ask'  => $asset->ask_price,
                            ]);

                            // following code will add values to asset_history, which will be used to retrieve data in range of 10 minutes in case of websocket failed,
                            // this is wrong logic but requested by company, and should be handeled in another way someday

                         
                        }
                    } 
                });

                // Handle WebSocket disconnection
                $conn->on('close', function () use ($wsUrl, $connector, $loop, $subscribeMessage) {
                    //sleep(3);
                    $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
                });

                // Handle errors
                $conn->on('error', function ($e) {
                    echo "Error: {$e->getMessage()}\n";
                });
            },
            function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                //sleep(5);
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
            }
        );
    }
}
*/


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use React\EventLoop\Factory;

class GetStockAssets extends Command
{
    protected $signature = 'get:stock-assets';
    protected $description = 'Listen to EOD WebSocket with optimized DB handling';

    // مصفوفة للتخزين المؤقت في الذاكرة
    protected $assetsCache = [];

    public function handle()
    {
        // 1. جلب الأصول مرة واحدة فقط عند تشغيل السكريبت
        $assets = Asset::where('type', 'Stocks')->get();
        foreach ($assets as $asset) {
            $this->assetsCache[strtoupper($asset->symbol)] = $asset;
        }

        if (empty($this->assetsCache)) {
            $this->error('⚠️ No Stocks assets found.');
            return;
        }

        $symbols = implode(",", array_keys($this->assetsCache));
        $subscribeMessage = json_encode(["action" => "subscribe", "symbols" => $symbols]);
        $wsUrl = "wss://ws.eodhistoricaldata.com/ws/us-quote?api_token=67f4cea78e4f60.22404437";

        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop, $subscribeMessage)
    {
        $connector($wsUrl)->then(function (WebSocket $conn) use ($subscribeMessage) {
            $conn->send($subscribeMessage);

            $conn->on('message', function ($data) {
                $response = json_decode($data, true);
                if (!isset($response['s'], $response['bp'], $response['ap'])) return;

                $symbol = strtoupper($response['s']);
                $bidPrice = $response['bp'];
                $askPrice = $response['ap'];

                // 2. البحث في الذاكرة (RAM) وليس في قاعدة البيانات
                if (isset($this->assetsCache[$symbol])) {
                    $asset = $this->assetsCache[$symbol];

                    // تحديث فقط في حال تغير السعر
                    if ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice) {
                        $asset->update([
                            'bid_price' => $bidPrice,
                            'ask_price' => $askPrice,
                            'last_bid'  => $asset->bid_price,
                            'last_ask'  => $asset->ask_price,
                        ]);
                        // تحديث النسخة الموجودة في الذاكرة أيضاً
                        $asset->bid_price = $bidPrice;
                        $asset->ask_price = $askPrice;
                    }
                }
            });
        }, function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
        });
    }
}

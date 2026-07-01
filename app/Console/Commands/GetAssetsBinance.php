<?php
/*
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use App\Models\AssetsHistory;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Factory;
use Carbon\Carbon;

class GetAssetsBinance extends Command
{
    protected $signature = 'get:assetsBinance';
    protected $description = 'Listen to Binance WebSocket for real-time bid/ask prices';

    public function handle()
    {
        $symbols = Asset::where('type', 'Crypto')
            ->where('is_active', 1)
            ->pluck('symbol')
            ->map(fn($s) => strtolower($s))
            ->toArray();

        if (empty($symbols)) {
            $this->error('No Crypto assets found in the database.');
            return;
        }

        // Build the WebSocket stream URL with multiple symbols
        $streams = implode('/', array_map(fn($s) => "{$s}@bookTicker", $symbols));
        $wsUrl = "wss://stream.binance.com:9443/stream?streams=$streams";

        $loop = Factory::create();

        // Delay the connection by 1 second before starting
        $loop->addTimer(1.0, function () use ($loop, $wsUrl) {
            $connector = new Connector($loop);
            $this->startWebSocket($wsUrl, $connector, $loop);
        });

        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop)
    {
        $connector($wsUrl)->then(
            function (WebSocket $conn) use ($wsUrl, $connector, $loop) {
                $conn->on('message', function ($data) {
                    $message = json_decode($data, true);
                    static $lastProcessed = [];
                    $symbol = $message['data']['s'] ?? null;
                    $now = microtime(true);

//if (isset($lastProcessed[$symbol]) && ($now - $lastProcessed[$symbol]) < 0.5) {
//    return;
//}
$lastProcessed[$symbol] = $now;

                    

                    if (!isset($message['data'])) {//&& 1 == 2
                    
                        //this code added to get data from history in case of websocket didn't work, it's wrong logic but requested by Walid
                       // $assets = Asset::where('type', 'Crypto')->where('is_active', 1)->get();
                       // foreach ($assets as $asset) {
                           

                           
                      //  }
// end of retrieve data from history
                        return;
                    }

                    $data = $message['data'];
                    $symbol = $data['s'];
                    $bidPrice = $data['b'] ?? null;
                    $askPrice = $data['a'] ?? null;

                    if ($bidPrice && $askPrice) {
                        $asset = Asset::where('symbol', $symbol)->where('is_active', 1)->first();
                        if ($asset && ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice)) {
                            $asset->update([
                                'last_bid' => $asset->bid_price,
                                'last_ask' => $asset->ask_price,
                                'bid_price' => $bidPrice,
                                'ask_price' => $askPrice,
                            ]);

                            //following code will add values to asset_history, which will be used to retrieve data in range of 10 minutes in case of websocket failed,
                        //this is wrong logic but requested by company, and should be handeled in another way someday
                 
                        }
                    }
                });

                // Reconnect automatically on close
                $conn->on('close', function () use ($wsUrl, $connector, $loop) {
                    $this->startWebSocket($wsUrl, $connector, $loop);
                });
            },
            function ($e) use ($wsUrl, $connector, $loop) {
                // Reconnect automatically on error
                $this->startWebSocket($wsUrl, $connector, $loop);
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

class GetAssetsBinance extends Command
{
    protected $signature = 'get:assetsBinance';
    protected $description = 'Listen to Binance WebSocket with optimized DB handling';

    protected $assetsCache = [];

    public function handle()
    {
        // 1. جلب الأصول وتخزينها في الذاكرة
        $assets = Asset::where('type', 'Crypto')->where('is_active', 1)->get();
        foreach ($assets as $asset) {
            $this->assetsCache[strtolower($asset->symbol)] = $asset;
        }

        if (empty($this->assetsCache)) {
            $this->error('No Crypto assets found.');
            return;
        }

        $symbols = array_map(fn($s) => "{$s}@bookTicker", array_keys($this->assetsCache));
        $streams = implode('/', $symbols);
        $wsUrl = "wss://stream.binance.com:9443/stream?streams=$streams";

        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop);
        $loop->run();
    }

    /*private function startWebSocket($wsUrl, $connector, $loop)
    {
        $connector($wsUrl)->then(function (WebSocket $conn) {
            $conn->on('message', function ($data) {
                $message = json_decode($data, true);
                if (!isset($message['data'])) return;

                $data = $message['data'];
                $symbol = $data['s']; // الرمز كما يأتي من بينانس
                $bidPrice = $data['b'] ?? null;
                $askPrice = $data['a'] ?? null;

                // 2. البحث في الذاكرة (RAM) وليس قاعدة البيانات
                if ($bidPrice && $askPrice && isset($this->assetsCache[strtolower($symbol)])) {
                    $asset = $this->assetsCache[strtolower($symbol)];

                    if ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice) {
                        $asset->update([
                            'last_bid' => $asset->bid_price,
                            'last_ask' => $asset->ask_price,
                            'bid_price' => $bidPrice,
                            'ask_price' => $askPrice,
                        ]);

                        // تحديث النسخة في الذاكرة
                        $asset->bid_price = $bidPrice;
                        $asset->ask_price = $askPrice;
                    }
                }
            });
        }, function ($e) use ($wsUrl, $connector, $loop) {
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop);
        });
    }*/
    private function startWebSocket($wsUrl, $connector, $loop)
    {
        // 1. أضفنا المتغيرات المطلوبة لإعادة الاتصال هنا في use
        $connector($wsUrl)->then(function (WebSocket $conn) use ($wsUrl, $connector, $loop) {
            $conn->on('message', function ($data) {
                $message = json_decode($data, true);
                if (!isset($message['data'])) return;

                $data = $message['data'];
                $symbol = $data['s']; // الرمز كما يأتي من بينانس
                $bidPrice = $data['b'] ?? null;
                $askPrice = $data['askPr'] ?? null; // تصحيح بسيط: Binance تستخدم 'a' لـ ask price وليس 'askPr' كما في Bitget.
                $askPrice = $data['a'] ?? null; // التعديل الصحيح

                // 2. البحث في الذاكرة (RAM)
                if ($bidPrice && $askPrice && isset($this->assetsCache[strtolower($symbol)])) {
                    $asset = $this->assetsCache[strtolower($symbol)];

                    if ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice) {
                        $asset->update([
                            'last_bid'  => $asset->bid_price,
                            'last_ask'  => $asset->ask_price,
                            'bid_price' => $bidPrice,
                            'ask_price' => $askPrice,
                        ]);

                        // تحديث النسخة في الذاكرة
                        $asset->bid_price = $bidPrice;
                        $asset->ask_price = $askPrice;
                    }
                }
            });

            // 2. ⚠️ إضافة حدث إعادة الاتصال عند انقطاع السوكيت
            $conn->on('close', function ($code = null, $reason = null) use ($wsUrl, $connector, $loop) {
                sleep(2); // الانتظار قليلاً قبل المحاولة
                $this->startWebSocket($wsUrl, $connector, $loop);
            });

        }, function ($e) use ($wsUrl, $connector, $loop) {
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop);
        });
    }
}
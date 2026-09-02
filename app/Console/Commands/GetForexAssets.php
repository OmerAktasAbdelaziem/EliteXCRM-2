<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use React\EventLoop\Factory;

class GetForexAssets extends Command
{
    protected $signature = 'get:forex-assets';
    protected $description = 'Listen to Forex WebSocket and print output to terminal';

    protected $assetsCache = [];

    public function handle()
    {
        $this->info("🚀 Starting Forex WebSocket...");

        // 1. جلب أصول الـ Forex وتخزينها في الذاكرة
        $assets = Asset::where('type', 'Forex')->where('is_active', 1)->get();
        foreach ($assets as $asset) {
            $this->assetsCache[strtoupper($asset->symbol)] = $asset;
        }

        if (empty($this->assetsCache)) {
            $this->error('⚠️ No active Forex assets found in Database.');
            return;
        }

        $symbols = implode(",", array_keys($this->assetsCache));
        $this->info("📌 Subscribing to symbols: {$symbols}");

        $subscribeMessage = json_encode(["action" => "subscribe", "symbols" => $symbols]);
        $wsUrl = "wss://ws.eodhistoricaldata.com/ws/forex?api_token=67f4cea78e4f60.22404437";

        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop, $subscribeMessage)
    {
        $connector($wsUrl)->then(function (WebSocket $conn) use ($wsUrl, $connector, $loop, $subscribeMessage) {
            
            echo "✅ Connected successfully to WebSocket!\n";
            $conn->send($subscribeMessage);

            $conn->on('message', function ($data) {
                // 1️⃣ طباعة البيانات الخام القادمة من السيرفر مباشرة
                echo "\n📡 Raw Data Received: " . $data . "\n";

                $response = json_decode($data, true);
                
                if (!isset($response['s'], $response['b'], $response['a'])) {
                    echo "⚠️ Data skipped: Missing symbol(s), bid(b) or ask(a) keys.\n";
                    return;
                }

                $symbol = strtoupper($response['s']);
                $bidPrice = $response['b'];
                $askPrice = $response['a'];

                // معالجة تنسيق أرقام الـ Forex
                if (strlen(substr(strrchr((string)$askPrice, "."), 1)) > 5) {
                    $askPrice = number_format((float)$askPrice, 5, '.', '');
                }
                if (strlen(substr(strrchr((string)$bidPrice, "."), 1)) > 5) {
                    $bidPrice = number_format((float)$bidPrice, 5, '.', '');
                }

                // 2️⃣ التحقق مما إذا كان الرمز موجود في الذاكرة (قاعدة البيانات)
                if (isset($this->assetsCache[$symbol])) {
                    $asset = $this->assetsCache[$symbol];

                    if ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice) {
                        
                        // 3️⃣ تحديث قاعدة البيانات
                        $asset->update([
                            'bid_price' => $bidPrice,
                            'ask_price' => $askPrice,
                            'last_bid'  => $asset->bid_price,
                            'last_ask'  => $asset->ask_price,
                        ]);

                        // تحديث الذاكرة
                        $asset->bid_price = $bidPrice;
                        $asset->ask_price = $askPrice;

                        echo "🟢 UPDATED [{$symbol}] -> Bid: {$bidPrice} | Ask: {$askPrice}\n";
                    } else {
                        echo "⚪ NO CHANGE [{$symbol}] -> Prices are identical.\n";
                    }
                } else {
                    // 4️⃣ الرمز غير موجود في قاعدة بياناتك!
                    echo "❌ MISMATCH: Received [{$symbol}] from WebSocket, but it's NOT in your database (Check spelling like EURUSD vs EUR/USD)\n";
                }
            });

            $conn->on('close', function ($code = null, $reason = null) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                echo "\n🔌 Connection Closed. Reconnecting in 2 seconds...\n";
                sleep(2);
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage); 
            });

        }, function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
            echo "🚨 Error Could not connect: {$e->getMessage()}\n";
            echo "🔄 Retrying in 5 seconds...\n";
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
        });
    }
}
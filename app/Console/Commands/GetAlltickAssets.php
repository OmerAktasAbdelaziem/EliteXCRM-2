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

    protected $assetsCache = [];

    public function handle()
    {
        $assets = Asset::all();

        // للتأكد من أننا لا نرسل أكثر من 100 رمز (لتجنب حظر الخطة)
        $count = 0;
        foreach ($assets as $asset) {
            if ($count >= 100) break; // أمان إضافي للتأكد من عدم تجاوز الخطة
            $this->assetsCache[$asset->symbol] = $asset;
            $count++;
        }

        $wsUrl = "wss://quote.alltick.co/quote-b-ws-api?token=5cec652a95a61e8cd8d380a4b286d282-c-app";
        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop);
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop)
    {
        $connector($wsUrl)->then(function (WebSocket $conn) use ($wsUrl, $connector, $loop) {
            
            $this->info("Connected to WebSocket successfully!");

            $symbols = [];
            foreach (array_keys($this->assetsCache) as $symbol) {
                $symbols[] = ['code' => $symbol];
            }

            // تقسيم الرموز إلى 5 دفعات (كل دفعة 20 رمز)
            $chunks = array_chunk($symbols, 20);
            $seqId = 1;
            $delay = 0;

            foreach ($chunks as $index => $chunk) {
                $subscribeMessage = json_encode([
                    "cmd_id" => 22002,
                    "seq_id" => $seqId++,
                    "trace"  => "chunk_" . $index, // وضعنا اسم الدفعة هنا لنتتبعها
                    "data"   => [
                        "symbol_list" => $chunk
                    ]
                ]);
                
                // إرسال الدفعات بفاصل ثانية كاملة بين كل دفعة
                if ($delay === 0) {
                    $conn->send($subscribeMessage);
                    $this->info("Sent Chunk 0 (20 symbols)");
                } else {
                    $loop->addTimer($delay, function () use ($conn, $subscribeMessage, $index) {
                        $conn->send($subscribeMessage);
                        $this->info("Sent Chunk {$index} (20 symbols)");
                    });
                }
                $delay += 1.0; 
            }

            $conn->on('message', function ($data) {
                $response = json_decode($data, true);

                // ==========================================
                // جزء الديباج (Debugging) - هذا سيكشف لك المشكلة
                // ==========================================
                // إذا كان الرد عبارة عن رسالة اشتراك أو خطأ وليس أسعاراً
                if (isset($response['cmd_id']) && $response['cmd_id'] !== 22004) {
                     $this->warn("Server Response: " . $data);
                }

                if (!isset($response['data'])) return;
                
                $responseData = $response['data'];
                $symbol = $responseData['code'] ?? null;

                if (!$symbol || empty($responseData['bids']) || empty($responseData['asks'])) {
                    return; 
                }

                $bidPrice = $responseData['bids'][0]['price'];
                $askPrice = $responseData['asks'][0]['price'];

                if (isset($this->assetsCache[$symbol])) {
                    $asset = $this->assetsCache[$symbol];
                    
                    if ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice) {
                        
                        $asset->update([
                            'bid_price' => $bidPrice,
                            'ask_price' => $askPrice,
                            'last_bid'  => $asset->bid_price,
                            'last_ask'  => $asset->ask_price,
                        ]);

                        $asset->bid_price = $bidPrice;
                        $asset->ask_price = $askPrice;
                    }
                }
            });

            $conn->on('close', function ($code = null, $reason = null) use ($wsUrl, $connector, $loop) {
                $this->error("Connection closed. Reason: {$reason}");
                sleep(2);
                $this->startWebSocket($wsUrl, $connector, $loop);
            });

        }, function ($e) use ($wsUrl, $connector, $loop) {
            $this->error("Connection Failed. Retrying...");
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop);
        });
    }
}
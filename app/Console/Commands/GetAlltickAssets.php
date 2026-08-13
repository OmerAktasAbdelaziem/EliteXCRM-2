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

        foreach ($assets as $asset) {
            $this->assetsCache[$asset->symbol] = $asset;
        }

        $wsUrl = "wss://quote.alltick.co/quote-b-ws-api?token=5fthfth4567567ytjtyj86d282-c-app";
        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop);
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop)
    {
        $connector($wsUrl)->then(function (WebSocket $conn) use ($wsUrl, $connector, $loop) {
            
            $symbols = [];
            foreach (array_keys($this->assetsCache) as $symbol) {
                $symbols[] = ['code' => $symbol];
            }

            // 1. تقسيم الرموز إلى دفعات (مثلاً 30 رمز في كل طلب) لتجنب قيود السيرفر
            $chunks = array_chunk($symbols, 30);
            $seqId = 1;

            foreach ($chunks as $chunk) {
                $subscribeMessage = json_encode([
                    "cmd_id" => 22002,
                    "seq_id" => $seqId++, // زيادة الـ seq_id لكل طلب
                    "trace"  => "202607301140",
                    "data"   => [
                        "symbol_list" => $chunk
                    ]
                ]);
                
                $conn->send($subscribeMessage);
            }

            $conn->on('message', function ($data) {
                $response = json_decode($data, true);

                if (!isset($response['data'])) return;

                $responseData = $response['data'];
                $symbol = $responseData['code'] ?? null;

                // 2. التحقق من وجود بيانات الأسعار لتجنب توقف السكربت (Crash)
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
                sleep(2);
                $this->startWebSocket($wsUrl, $connector, $loop);
            });

        }, function ($e) use ($wsUrl, $connector, $loop) {
            sleep(5);
            $this->startWebSocket($wsUrl, $connector, $loop);
        });
    }
}
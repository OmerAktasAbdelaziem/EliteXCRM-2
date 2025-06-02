<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Factory;

class GetAssetsBinance extends Command
{
    protected $signature = 'get:assetsBinance';
    protected $description = 'Listen to Binance WebSocket for real-time bid/ask prices';

    public function handle()
    {
        $symbols = Asset::where('type', 'Crypto')->where('is_active', 1)->pluck('symbol')->map(fn($s) => strtolower($s))->toArray();

        if (empty($symbols)) {
            $this->error('No Crypto assets found in the database.');
            return;
        }

        // Create the WebSocket URL with multiple streams
        $streams = implode('/', array_map(fn($s) => "{$s}@bookTicker", $symbols));
        $wsUrl = "wss://stream.binance.com:9443/stream?streams=$streams";

        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop);
        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop)
    {
        $connector($wsUrl)->then(
            function (WebSocket $conn) use ($wsUrl, $connector, $loop) {
                $conn->on('message', function ($data) {
                    $message = json_decode($data, true);

                    if (!isset($message['data'])) {
                        return;
                    }

                    $data = $message['data'];
                    $symbol = $data['s'];
                    $bidPrice = $data['b'] ?? null;
                    $askPrice = $data['a'] ?? null;

                    if ($bidPrice && $askPrice) {
                        $asset = Asset::where('symbol', $symbol)->where('is_active', 1)->first();
                        if ($asset) {
                            $asset->update([
                                'last_bid'  => $asset->bid_price,
                                'last_ask'  => $asset->ask_price,
                                'bid_price' => $bidPrice,
                                'ask_price' => $askPrice,
                            ]);
                        }
                    }
                });

                $conn->on('close', function () use ($wsUrl, $connector, $loop) {
                    $this->startWebSocket($wsUrl, $connector, $loop); // Reconnect on close
                });
            },
            function ($e) use ($wsUrl, $connector, $loop) {
                $this->startWebSocket($wsUrl, $connector, $loop); // Reconnect on error
            }
        );
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use React\EventLoop\Factory;

class GetAssets extends Command
{
    protected $signature = 'get:assets';
    protected $description = 'Listen to bitget WebSocket for real-time bid/ask prices with multiple connections';

    public function handle()
    {
        $symbols = Asset::where('type', 'Crypto')->where('is_active',1)->pluck('symbol')->map(fn($s) => strtoupper($s))->toArray();

        if (empty($symbols)) {
            $this->error('No Crypto assets found in the database.');
            return;
        }

        $wsUrl = "wss://ws.bitget.com/v2/ws/public";
        $loop = Factory::create();
        $connector = new Connector($loop);

        // Loop through each chunk and create a connection for each chunk
        $args = array_map(fn($symbol) => [
            'instType' => 'SPOT',
            'channel' => 'ticker',
            'instId' => strtoupper($symbol),
        ], $symbols);

        $subscribeMessage = json_encode([
            "op" => "subscribe",
            "args" => $args
        ]);

        $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);

        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop, $subscribeMessage)
    {
        $connector($wsUrl)->then(
            function (WebSocket $conn) use ($wsUrl, $subscribeMessage, $connector, $loop) {
                $conn->send($subscribeMessage);

                $conn->on('message', function ($data) {
                    $response = json_decode($data, true);

                    if (isset($response['data']) && is_array($response['data'])) {
                        $symbol = strtoupper($response['data'][0]['instId']);
                        $bidPrice = $response['data'][0]['bidPr'] ?? null;
                        $askPrice = $response['data'][0]['askPr'] ?? null;

                        if ($bidPrice && $askPrice) {
                            $asset = Asset::where('symbol', $symbol)->where('is_active',1)->first();
                            if ($asset) {
                                $asset->update([
                                    'bid_price' => $bidPrice,
                                    'ask_price' => $askPrice,
                                    'last_bid'  => $asset->bid_price,
                                    'last_ask'  => $asset->ask_price,
                                ]);
                            }
                        }
                    }
                });

                $conn->on('close', function () use ($wsUrl, $connector, $loop, $subscribeMessage) {
                    $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage); // Reconnect
                });
            },
            function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
            }
        );
    }
}

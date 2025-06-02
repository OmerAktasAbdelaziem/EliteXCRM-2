<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use React\EventLoop\Factory;

class GetStockAssets2 extends Command
{
    protected $signature = 'get:stock-assets2';
    protected $description = 'Listen to EOD Historical Data WebSocket for real-time Stocks bid/ask prices';

    public function handle()
    {
        // Fetch Stock symbols from the database
        $symbols = Asset::where('type', 'Stocks')->orderBy('id', 'desc')->limit(22)->pluck('symbol')->map(fn($s) => strtoupper($s))->toArray();

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

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return;
                    }

                    if (isset($response['s'], $response['bp'], $response['ap'])) {
                        $symbol = strtoupper($response['s']);
                        $bidPrice = $response['bp'];
                        $askPrice = $response['ap'];

                        // Update database with real-time bid/ask prices
                        $asset = Asset::where('symbol', $symbol)->first();
                        if ($asset) {
                            $asset->update([
                                'bid_price' => $bidPrice,
                                'ask_price' => $askPrice,
                                'last_bid'  => $asset->bid_price,
                                'last_ask'  => $asset->ask_price,
                            ]);
                        }
                    }
                });

                // Handle WebSocket disconnection
                $conn->on('close', function () use ($wsUrl, $connector, $loop, $subscribeMessage) {
                    sleep(3);
                    $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
                });

                // Handle errors
                $conn->on('error', function ($e) {
                });
            },
            function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                sleep(5);
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
            }
        );
    }
}
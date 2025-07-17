<?php

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
                        $assets = Asset::where('type', 'Crypto')->where('is_active', 1)->get();
                        foreach ($assets as $asset) {
                            $assetHistory = AssetsHistory::where('type', 'Crypto')
                                ->where('symbol', $asset->symbol)
                                ->where('created_at', '>=', Carbon::now()->subMinutes(10))
                                ->first();

                            if (isset($assetHistory->bid_price) && isset($assetHistory->ask_price)) {
                                $asset->update([
                                    'bid_price' => $assetHistory->bid_price,
                                    'ask_price' => $assetHistory->ask_price,
                                    'last_bid' => $asset->bid_price,
                                    'last_ask' => $asset->ask_price,
                                ]);
                            }
                        }
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
                            AssetsHistory::create([
                                'name' => $asset->name,
                                'type' => $asset->type,
                                'category' => $asset->category,
                                'symbol' => $asset->symbol,
                                'currency' => $asset->currency,
                                'bid_price' => $bidPrice,
                                'ask_price' => $askPrice,
                                'last_bid' => $asset->bid_price,
                                'last_ask' => $asset->ask_price,
                            ]);
                             //end of adding to assets_history
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
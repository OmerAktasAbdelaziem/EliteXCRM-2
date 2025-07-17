<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use App\Models\AssetsHistory;
use React\EventLoop\Factory;
use Carbon\Carbon;

class GetAssets extends Command
{
    protected $signature = 'get:assets';
    protected $description = 'Listen to bitget WebSocket for real-time bid/ask prices with multiple connections';

    public function handle()
    {
        $symbols = Asset::where('type', 'Crypto')
            ->where('is_active', 1)
            ->pluck('symbol')
            ->map(fn($s) => strtoupper($s))
            ->toArray();

        if (empty($symbols)) {
            $this->error('No Crypto assets found in the database.');
            return;
        }

        $wsUrl = "wss://ws.bitget.com/v2/ws/public";
        $loop = Factory::create();

        $loop->addTimer(1.0, function () use ($loop, $symbols, $wsUrl) {
            $connector = new Connector($loop);

            $args = array_map(fn($symbol) => [
                'instType' => 'SPOT',
                'channel' => 'ticker',
                'instId' => $symbol,
            ], $symbols);

            $subscribeMessage = json_encode([
                "op" => "subscribe",
                "args" => $args
            ]);

            $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
        });

        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop, $subscribeMessage)
    {
        $connector($wsUrl)->then(
            function (WebSocket $conn) use ($wsUrl, $subscribeMessage, $connector, $loop) {
                $conn->send($subscribeMessage);

                $conn->on('message', function ($data) use ($loop) {
                    $response = json_decode($data, true);

                    static $lastProcessed = [];
                    $symbol = strtoupper($response['data'][0]['instId']?? null);
                    $now = microtime(true);

//if (isset($lastProcessed[$symbol]) && ($now - $lastProcessed[$symbol]) < 0.5) {
//    return;
//}
                    
$lastProcessed[$symbol] = $now;

                    
                    if (isset($response['data']) && is_array($response['data'])) {// && 1 == 2
                        $symbol = strtoupper($response['data'][0]['instId']);
                        $bidPrice = $response['data'][0]['bidPr'] ?? null;
                        $askPrice = $response['data'][0]['askPr'] ?? null;

                        if ($bidPrice && $askPrice) {
                            $asset = Asset::where('symbol', $symbol)->where('is_active', 1)->first();
                            if ($asset && ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice)) {
                                $asset->update([
                                    'bid_price' => $bidPrice,
                                    'ask_price' => $askPrice,
                                    'last_bid' => $asset->bid_price,
                                    'last_ask' => $asset->ask_price,
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
                    } else {
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
                    }
                });

                $conn->on('close', function () use ($wsUrl, $connector, $loop, $subscribeMessage) {
                    $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
                });
            },
            function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
            }
        );
    }
}
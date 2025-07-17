<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use App\Models\AssetsHistory;
use React\EventLoop\Factory;
use Carbon\Carbon;

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
                    static $lastProcessed = [];
                    $symbol = $symbol = strtoupper($response['s']?? null);
                    $now = microtime(true);

//if (isset($lastProcessed[$symbol]) && ($now - $lastProcessed[$symbol]) < 0.5) {
//    return;
//}
$lastProcessed[$symbol] = $now;
                    $now = microtime(true);

                    // Process messages only every 0.5 seconds to reduce DB load
                    if (($now - $lastProcessed[$symbol]) < 0.5) {
                        return;
                    }

                    $lastProcessed = $now;

                   

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
                            // end of adding to assets_history
                        }
                    } else {
                        // this code added to get data from history in case of websocket didn't work, it's wrong logic but requested by Walid
                        $assets = Asset::where('type', 'Stocks')->where('is_active', 1)->get();
                        foreach ($assets as $asset) {
                            $assetHistory = AssetsHistory::where('type', 'Stocks')
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

                // Handle WebSocket disconnection
                $conn->on('close', function () use ($wsUrl, $connector, $loop, $subscribeMessage) {
                    //sleep(3);
                    $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
                });

                // Handle errors
                $conn->on('error', function ($e) {
                    // Silent fail or logging can be added here
                });
            },
            function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                //sleep(5);
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);
            }
        );
    }
}
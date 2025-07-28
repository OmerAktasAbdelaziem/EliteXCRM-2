<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use App\Models\Asset;
use App\Models\AssetsHistory;
use React\EventLoop\Factory;
use Carbon\Carbon;

class GetForexAssets extends Command
{
    protected $signature = 'get:forex-assets';
    protected $description = 'Listen to EOD Historical Data WebSocket for real-time Forex bid/ask prices';

    public function handle()
    {
        $symbols = Asset::where('type', 'Forex')->where('is_active', 1)->pluck('symbol')->map(fn($s) => strtoupper($s))->toArray();

//        if (empty($symbols)) {
//            $this->error('No Forex assets found in the database.');
//            return;
//        }

        $symbolsString = implode(",", $symbols);
        $subscribeMessage = json_encode([
            "action"  => "subscribe",
            "symbols" => $symbolsString
        ]);

        $wsUrl = "wss://ws.eodhistoricaldata.com/ws/forex?api_token=67f4cea78e4f60.22404437";

        $loop = Factory::create();
        $connector = new Connector($loop);

        $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage);

        $loop->run();
    }

    private function startWebSocket($wsUrl, $connector, $loop, $subscribeMessage)
    {
        $connector($wsUrl)->then(
            function (WebSocket $conn) use ($wsUrl, $subscribeMessage, $connector, $loop) {

                $conn->send($subscribeMessage);

                $conn->on('message', function ($data) {
                    //static $lastProcessed = [];
                    $symbol = strtoupper($response['s']?? null);
                    //$now = microtime(true);

/*if (isset($lastProcessed[$symbol]) && ($now - $lastProcessed[$symbol]) < 0.5) {
    return;
}*/
//$lastProcessed[$symbol] = $now;

                    $response = json_decode($data, true);

                    if (isset($response['s'], $response['b'], $response['a'])) { // && 1 == 2
                        $symbol = strtoupper($response['s']);
                        $bidPrice = $response['b'];
                        $askPrice = $response['a'];

                        $asset = Asset::where('symbol', $symbol)->first();
                        if ($asset && ($asset->bid_price != $bidPrice || $asset->ask_price != $askPrice)) {
                            if ($asset->category === 'Forex') {
                                if (strlen(substr(strrchr($askPrice, "."), 1)) > 5) {
                                    $askPrice = number_format((float)$askPrice, 5, '.', '');
                                }

                                if (strlen(substr(strrchr($bidPrice, "."), 1)) > 5) {
                                    $bidPrice = number_format((float)$bidPrice, 5, '.', '');
                                }
                            }

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
                        $assets = Asset::where('type', 'Forex')->where('is_active', 1)->get();
                        foreach ($assets as $asset) {
                            $assetHistory = AssetsHistory::where('type', 'Forex')
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
                    $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage); // Reconnect
                });
            },
            function ($e) use ($wsUrl, $connector, $loop, $subscribeMessage) {
                $this->startWebSocket($wsUrl, $connector, $loop, $subscribeMessage); // Reconnect on error
            }
        );
    }
}
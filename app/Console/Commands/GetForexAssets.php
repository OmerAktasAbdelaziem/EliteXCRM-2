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
    protected $description = 'Listen to EOD Historical Data WebSocket for real-time Forex bid/ask prices';

    public function handle()
    {
        $symbols = Asset::where('type', 'Forex')->where('is_active',1)->pluck('symbol')->map(fn($s) => strtoupper($s))->toArray();

        if (empty($symbols)) {
            $this->error('No Forex assets found in the database.');
            return;
        }

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
                    $response = json_decode($data, true);

                    if (isset($response['s'], $response['b'], $response['a'])) {
                        $symbol = strtoupper($response['s']);
                        $bidPrice = $response['b'];
                        $askPrice = $response['a'];

                        $asset = Asset::where('symbol', $symbol)->first();
                        if ($asset) {
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

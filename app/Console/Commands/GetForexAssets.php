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

    protected $description = 'Listen to Forex WebSocket in batches of 15 symbols';

    protected $assetsCache = [];

    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | Load all active Forex assets into memory
        |--------------------------------------------------------------------------
        */

        $assets = Asset::where('type', 'Forex')
            ->where('is_active', 1)
            ->get();

        foreach ($assets as $asset) {
            $this->assetsCache[strtoupper(trim($asset->symbol))] = $asset;
        }

        if (empty($this->assetsCache)) {
            $this->error('⚠️ No active Forex assets found.');
            return Command::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Get all symbols
        |--------------------------------------------------------------------------
        */

        $symbols = array_keys($this->assetsCache);

        /*
        |--------------------------------------------------------------------------
        | Split symbols into batches of 15
        |--------------------------------------------------------------------------
        */

        $batches = array_chunk($symbols, 15);

        $this->info('==============================================');
        $this->info('🚀 Starting Forex WebSocket');
        $this->info('📊 Total Symbols: ' . count($symbols));
        $this->info('📦 Total Batches: ' . count($batches));
        $this->info('==============================================');

        $wsUrl = 'wss://ws.eodhistoricaldata.com/ws/forex?api_token=67f4cea78e4f60.22404437';

        $loop = Factory::create();

        /*
        |--------------------------------------------------------------------------
        | Create a separate WebSocket connection for each batch
        |--------------------------------------------------------------------------
        */

        foreach ($batches as $batchIndex => $batchSymbols) {

            $batchNumber = $batchIndex + 1;

            $this->info('');
            $this->info("📦 Batch {$batchNumber}");
            $this->info('📊 Symbols: ' . implode(',', $batchSymbols));

            /*
             * Important:
             * Each batch gets its own connector.
             */
            $connector = new Connector($loop);

            $this->startWebSocket(
                $wsUrl,
                $connector,
                $loop,
                $batchSymbols,
                $batchNumber
            );
        }

        $loop->run();

        return Command::SUCCESS;
    }


    private function startWebSocket(
        string $wsUrl,
        Connector $connector,
        $loop,
        array $symbols,
        int $batchNumber
    ) {
        /*
        |--------------------------------------------------------------------------
        | Build subscription message for THIS batch only
        |--------------------------------------------------------------------------
        */

        $subscribeMessage = json_encode([
            'action'  => 'subscribe',
            'symbols' => implode(',', $symbols),
        ]);

        $this->info("🔌 Connecting Batch {$batchNumber}...");

        $connector($wsUrl)->then(

            function (WebSocket $conn) use (
                $wsUrl,
                $connector,
                $loop,
                $symbols,
                $batchNumber,
                $subscribeMessage
            ) {

                $this->info("✅ Batch {$batchNumber} connected");

                /*
                 * Subscribe only to this batch
                 */
                $conn->send($subscribeMessage);

                $this->info(
                    "📨 Batch {$batchNumber} subscription sent: "
                    . implode(',', $symbols)
                );

                $conn->on('message', function ($data) use ($batchNumber) {

                    $response = json_decode((string) $data, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $this->warn(
                            "⚠️ Batch {$batchNumber}: Invalid JSON"
                        );

                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Handle EODHD system messages
                    |--------------------------------------------------------------------------
                    */

                    if (isset($response['status_code'])) {

                        $statusCode = $response['status_code'];
                        $message = $response['message'] ?? '';

                        $this->info(
                            "📡 Batch {$batchNumber}: "
                            . "[{$statusCode}] {$message}"
                        );

                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Make sure this is a price message
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !isset($response['s']) ||
                        !isset($response['b']) ||
                        !isset($response['a'])
                    ) {
                        return;
                    }

                    $symbol = strtoupper(trim($response['s']));

                    $bidPrice = $response['b'];
                    $askPrice = $response['a'];

                    /*
                    |--------------------------------------------------------------------------
                    | Debug: show EVERY received symbol
                    |--------------------------------------------------------------------------
                    */

                    $this->line(
                        "📥 [Batch {$batchNumber}] {$symbol} "
                        . "| Bid: {$bidPrice} "
                        . "| Ask: {$askPrice}"
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Find asset in GLOBAL cache
                    |--------------------------------------------------------------------------
                    |
                    | This is important for XAUUSD.
                    | The cache contains ALL Forex assets,
                    | not just the current batch.
                    |
                    */

                    if (!isset($this->assetsCache[$symbol])) {

                        $this->warn(
                            "⚠️ {$symbol} received but NOT found in assetsCache"
                        );

                        return;
                    }

                    $asset = $this->assetsCache[$symbol];

                    /*
                    |--------------------------------------------------------------------------
                    | Format Forex prices
                    |--------------------------------------------------------------------------
                    */

                    if ($asset->category === 'Forex') {

                        if (
                            strpos((string) $askPrice, '.') !== false &&
                            strlen(
                                substr(
                                    strrchr((string) $askPrice, '.'),
                                    1
                                )
                            ) > 5
                        ) {
                            $askPrice = number_format(
                                (float) $askPrice,
                                5,
                                '.',
                                ''
                            );
                        }

                        if (
                            strpos((string) $bidPrice, '.') !== false &&
                            strlen(
                                substr(
                                    strrchr((string) $bidPrice, '.'),
                                    1
                                )
                            ) > 5
                        ) {
                            $bidPrice = number_format(
                                (float) $bidPrice,
                                5,
                                '.',
                                ''
                            );
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Update only if price changed
                    |--------------------------------------------------------------------------
                    */

                    if (
                        (string) $asset->bid_price === (string) $bidPrice &&
                        (string) $asset->ask_price === (string) $askPrice
                    ) {
                        return;
                    }

                    $oldBid = $asset->bid_price;
                    $oldAsk = $asset->ask_price;

                    /*
                    |--------------------------------------------------------------------------
                    | Update database
                    |--------------------------------------------------------------------------
                    */

                    $asset->update([
                        'bid_price' => $bidPrice,
                        'ask_price' => $askPrice,
                        'last_bid'  => $oldBid,
                        'last_ask'  => $oldAsk,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Update RAM cache
                    |--------------------------------------------------------------------------
                    */

                    $asset->bid_price = $bidPrice;
                    $asset->ask_price = $askPrice;

                    $this->info(
                        "💾 UPDATED {$symbol} "
                        . "| Bid: {$bidPrice} "
                        . "| Ask: {$askPrice}"
                    );
                });


                /*
                |--------------------------------------------------------------------------
                | Reconnect this batch only
                |--------------------------------------------------------------------------
                */

                $conn->on(
                    'close',
                    function ($code = null, $reason = null) use (
                        $wsUrl,
                        $connector,
                        $loop,
                        $symbols,
                        $batchNumber
                    ) {

                        $this->warn(
                            "⚠️ Batch {$batchNumber} disconnected "
                            . "(Code: {$code}). Reconnecting..."
                        );

                        /*
                         * Don't use sleep() inside React event loop.
                         */
                        $loop->addTimer(
                            2,
                            function () use (
                                $wsUrl,
                                $connector,
                                $loop,
                                $symbols,
                                $batchNumber
                            ) {
                                $this->startWebSocket(
                                    $wsUrl,
                                    $connector,
                                    $loop,
                                    $symbols,
                                    $batchNumber
                                );
                            }
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Error handler
                |--------------------------------------------------------------------------
                */

                $conn->on('error', function ($e) use ($batchNumber) {

                    $this->error(
                        "❌ Batch {$batchNumber} Error: "
                        . $e->getMessage()
                    );
                });
            },

            /*
            |--------------------------------------------------------------------------
            | Connection failed
            |--------------------------------------------------------------------------
            */

            function ($e) use (
                $wsUrl,
                $connector,
                $loop,
                $symbols,
                $batchNumber
            ) {

                $this->error(
                    "❌ Batch {$batchNumber} connection failed: "
                    . $e->getMessage()
                );

                $loop->addTimer(
                    5,
                    function () use (
                        $wsUrl,
                        $connector,
                        $loop,
                        $symbols,
                        $batchNumber
                    ) {

                        $this->startWebSocket(
                            $wsUrl,
                            $connector,
                            $loop,
                            $symbols,
                            $batchNumber
                        );
                    }
                );
            }
        );
    }
}
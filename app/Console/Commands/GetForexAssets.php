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

    protected $description = 'Listen to XAUUSD Forex WebSocket';

    protected $asset;

    public function handle()
    {
        $this->info('==========================================');
        $this->info('🚀 Starting XAUUSD WebSocket...');
        $this->info('==========================================');

        /*
         * جلب الذهب من قاعدة البيانات مرة واحدة فقط
         */
        $this->asset = Asset::where('symbol', 'XAUUSD')
            ->where('is_active', 1)
            ->first();

        if (!$this->asset) {
            $this->error('❌ XAUUSD not found or not active in database.');

            return Command::FAILURE;
        }

        $this->info("📊 Asset found: {$this->asset->symbol}");

        /*
         * الاشتراك فقط في الذهب
         */
        $subscribeMessage = json_encode([
            'action'  => 'subscribe',
            'symbols' => 'XAUUSD',
        ]);

        /*
         * Forex WebSocket
         */
        $wsUrl = 'wss://ws.eodhistoricaldata.com/ws/forex?api_token=67f4cea78e4f60.22404437';

        $loop = Factory::create();

        $connector = new Connector($loop);

        $this->startWebSocket(
            $wsUrl,
            $connector,
            $loop,
            $subscribeMessage
        );

        $loop->run();

        return Command::SUCCESS;
    }

    private function startWebSocket(
        string $wsUrl,
        Connector $connector,
        $loop,
        string $subscribeMessage
    ) {
        $this->info('🔌 Connecting to WebSocket...');

        $connector($wsUrl)->then(

            function (WebSocket $conn) use (
                $wsUrl,
                $connector,
                $loop,
                $subscribeMessage
            ) {
                $this->info('✅ Connected successfully!');

                /*
                 * إرسال الاشتراك
                 */
                $conn->send($subscribeMessage);

                $this->info(
                    '📨 Subscribed to XAUUSD'
                );

                /*
                 * استقبال البيانات
                 */
                $conn->on('message', function ($data) {

                    $response = json_decode($data, true);

                    /*
                     * التأكد من صحة JSON
                     */
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $this->warn(
                            '⚠️ Invalid JSON: ' . $data
                        );

                        return;
                    }

                    /*
                     * رسائل النظام مثل Authorized
                     */
                    if (isset($response['status_code'])) {

                        $this->info(
                            "📡 {$response['message']}"
                        );

                        return;
                    }

                    /*
                     * التأكد من وجود بيانات السعر
                     */
                    if (
                        !isset($response['s']) ||
                        !isset($response['b']) ||
                        !isset($response['a'])
                    ) {
                        return;
                    }

                    $symbol = strtoupper($response['s']);

                    /*
                     * نتأكد أننا نستقبل الذهب فقط
                     */
                    if ($symbol !== 'XAUUSD') {
                        return;
                    }

                    $bidPrice = $response['b'];
                    $askPrice = $response['a'];

                    /*
                     * عرض البيانات القادمة
                     */
                    $this->line(
                        "📈 {$symbol} | BID: {$bidPrice} | ASK: {$askPrice}"
                    );

                    /*
                     * حفظ الأسعار القديمة
                     */
                    $oldBid = $this->asset->bid_price;
                    $oldAsk = $this->asset->ask_price;

                    /*
                     * لا تحدث قاعدة البيانات إذا لم يتغير شيء
                     */
                    if (
                        (string) $oldBid === (string) $bidPrice &&
                        (string) $oldAsk === (string) $askPrice
                    ) {
                        return;
                    }

                    /*
                     * تحديث قاعدة البيانات
                     */
                    $this->asset->update([
                        'bid_price' => $bidPrice,
                        'ask_price' => $askPrice,
                        'last_bid'  => $oldBid,
                        'last_ask'  => $oldAsk,
                    ]);

                    /*
                     * تحديث البيانات الموجودة في RAM
                     */
                    $this->asset->bid_price = $bidPrice;
                    $this->asset->ask_price = $askPrice;

                    $this->info(
                        "💾 UPDATED XAUUSD | " .
                        "BID: {$oldBid} → {$bidPrice} | " .
                        "ASK: {$oldAsk} → {$askPrice}"
                    );
                });

                /*
                 * عند حدوث خطأ
                 */
                $conn->on('error', function ($e) {

                    $this->error(
                        '❌ WebSocket Error: ' . $e->getMessage()
                    );

                    \Log::error(
                        'XAUUSD WebSocket Error: ' . $e->getMessage()
                    );
                });

                /*
                 * عند انقطاع الاتصال
                 */
                $conn->on(
                    'close',
                    function (
                        $code = null,
                        $reason = null
                    ) use (
                        $wsUrl,
                        $connector,
                        $loop,
                        $subscribeMessage
                    ) {

                        $this->warn(
                            "⚠️ Connection closed. Code: {$code}"
                        );

                        $this->warn(
                            '🔄 Reconnecting in 2 seconds...'
                        );

                        $loop->addTimer(
                            2,
                            function () use (
                                $wsUrl,
                                $connector,
                                $loop,
                                $subscribeMessage
                            ) {
                                $this->startWebSocket(
                                    $wsUrl,
                                    $connector,
                                    $loop,
                                    $subscribeMessage
                                );
                            }
                        );
                    }
                );
            },

            /*
             * إذا فشل الاتصال
             */
            function ($e) use (
                $wsUrl,
                $connector,
                $loop,
                $subscribeMessage
            ) {

                $this->error(
                    '❌ Connection failed: ' . $e->getMessage()
                );

                $this->warn(
                    '🔄 Retrying in 5 seconds...'
                );

                $loop->addTimer(
                    5,
                    function () use (
                        $wsUrl,
                        $connector,
                        $loop,
                        $subscribeMessage
                    ) {
                        $this->startWebSocket(
                            $wsUrl,
                            $connector,
                            $loop,
                            $subscribeMessage
                        );
                    }
                );
            }
        );
    }
}
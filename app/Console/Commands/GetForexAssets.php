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

    protected $description = 'Listen to Forex WebSocket with multiple symbol groups';

    protected $assetsCache = [];

    public function handle()
    {
        /*
         * جلب أصول الفوركس وتخزينها في RAM
         */
        $assets = Asset::where('type', 'Forex')
            ->where('is_active', 1)
            ->get();

        foreach ($assets as $asset) {
            $this->assetsCache[strtoupper($asset->symbol)] = $asset;
        }

        if (empty($this->assetsCache)) {
            $this->error('⚠️ No active Forex assets found.');
            return Command::FAILURE;
        }

        /*
         * جميع الرموز
         */
        $symbols = array_keys($this->assetsCache);

        /*
         * عدد الرموز في كل WebSocket
         *
         * يمكنك تغيير 5 إلى 10 أو الرقم المناسب
         */
        $symbolGroups = array_chunk($symbols, 5);

        $this->info('==========================================');
        $this->info('🚀 Starting Forex WebSockets');
        $this->info('📊 Total symbols: ' . count($symbols));
        $this->info('🔌 Total connections: ' . count($symbolGroups));
        $this->info('==========================================');

        $wsUrl = 'wss://ws.eodhistoricaldata.com/ws/forex?api_token=67f4cea78e4f60.22404437';

        $loop = Factory::create();
        $connector = new Connector($loop);

        /*
         * فتح اتصال لكل مجموعة
         */
        foreach ($symbolGroups as $index => $group) {

            $subscribeMessage = json_encode([
                'action' => 'subscribe',
                'symbols' => implode(',', $group),
            ]);

            $this->info(
                '📡 Group ' . ($index + 1) . ': ' . implode(',', $group)
            );

            $this->startWebSocket(
                $wsUrl,
                $connector,
                $loop,
                $subscribeMessage,
                $index + 1
            );
        }

        $loop->run();

        return Command::SUCCESS;
    }


    private function startWebSocket(
        string $wsUrl,
        Connector $connector,
        $loop,
        string $subscribeMessage,
        int $groupNumber
    ) {
        $this->info(
            "🔌 Connecting Group {$groupNumber}..."
        );

        $connector($wsUrl)->then(

            function (WebSocket $conn) use (
                $wsUrl,
                $connector,
                $loop,
                $subscribeMessage,
                $groupNumber
            ) {

                $this->info(
                    "✅ Group {$groupNumber} connected"
                );

                /*
                 * إرسال طلب الاشتراك
                 */
                $conn->send($subscribeMessage);

                $conn->on('message', function ($data) use ($groupNumber) {

                    $response = json_decode($data, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $this->warn(
                            "⚠️ Group {$groupNumber}: Invalid JSON"
                        );

                        return;
                    }

                    /*
                     * رسائل النظام
                     */
                    if (isset($response['status_code'])) {

                        $message = $response['message'] ?? '';

                        $this->info(
                            "📡 Group {$groupNumber}: {$message}"
                        );

                        return;
                    }

                    /*
                     * التأكد من وجود البيانات
                     */
                    if (
                        !isset($response['s']) ||
                        !isset($response['b']) ||
                        !isset($response['a'])
                    ) {
                        return;
                    }

                    $symbol = strtoupper($response['s']);

                    $bidPrice = $response['b'];
                    $askPrice = $response['a'];

                    /*
                     * تنسيق الأسعار عند الحاجة
                     */
                    if (
                        strpos((string) $askPrice, '.') !== false &&
                        strlen(substr(strrchr((string) $askPrice, '.'), 1)) > 5
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
                        strlen(substr(strrchr((string) $bidPrice, '.'), 1)) > 5
                    ) {
                        $bidPrice = number_format(
                            (float) $bidPrice,
                            5,
                            '.',
                            ''
                        );
                    }

                    /*
                     * البحث عن الأصل في RAM
                     */
                    if (!isset($this->assetsCache[$symbol])) {
                        $this->warn(
                            "⚠️ {$symbol} not found in cache"
                        );

                        return;
                    }

                    $asset = $this->assetsCache[$symbol];

                    /*
                     * لا نقوم بعملية UPDATE إذا لم يتغير السعر
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
                     * تحديث قاعدة البيانات
                     */
                    $asset->update([
                        'bid_price' => $bidPrice,
                        'ask_price' => $askPrice,
                        'last_bid'  => $oldBid,
                        'last_ask'  => $oldAsk,
                    ]);

                    /*
                     * تحديث RAM
                     */
                    $asset->bid_price = $bidPrice;
                    $asset->ask_price = $askPrice;

                    $this->line(
                        "📈 [Group {$groupNumber}] {$symbol} | " .
                        "Bid: {$bidPrice} | Ask: {$askPrice}"
                    );
                });


                /*
                 * عند انقطاع الاتصال
                 */
                $conn->on(
                    'close',
                    function ($code = null, $reason = null) use (
                        $wsUrl,
                        $connector,
                        $loop,
                        $subscribeMessage,
                        $groupNumber
                    ) {

                        $this->warn(
                            "⚠️ Group {$groupNumber} disconnected. Reconnecting..."
                        );

                        $loop->addTimer(
                            2,
                            function () use (
                                $wsUrl,
                                $connector,
                                $loop,
                                $subscribeMessage,
                                $groupNumber
                            ) {
                                $this->startWebSocket(
                                    $wsUrl,
                                    $connector,
                                    $loop,
                                    $subscribeMessage,
                                    $groupNumber
                                );
                            }
                        );
                    }
                );
            },

            /*
             * فشل الاتصال
             */
            function ($e) use (
                $wsUrl,
                $connector,
                $loop,
                $subscribeMessage,
                $groupNumber
            ) {

                $this->error(
                    "❌ Group {$groupNumber} connection failed: " .
                    $e->getMessage()
                );

                $loop->addTimer(
                    5,
                    function () use (
                        $wsUrl,
                        $connector,
                        $loop,
                        $subscribeMessage,
                        $groupNumber
                    ) {
                        $this->startWebSocket(
                            $wsUrl,
                            $connector,
                            $loop,
                            $subscribeMessage,
                            $groupNumber
                        );
                    }
                );
            }
        );
    }
}
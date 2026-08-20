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

    protected $description = 'Listen to Forex WebSocket with optimized performance and auto reconnect';

    /**
     * تخزين الأصول في الذاكرة
     *
     * الشكل:
     * [
     *     'XAUUSD' => Asset Object,
     *     'EURUSD' => Asset Object,
     * ]
     */
    protected $assetsCache = [];


    public function handle()
    {
        $this->info('==========================================');
        $this->info('🚀 Starting Forex WebSocket...');
        $this->info('==========================================');

        /*
         * جلب جميع أصول الفوركس الفعالة مرة واحدة فقط
         */
        $assets = Asset::where('type', 'Forex')
            ->where('is_active', 1)
            ->get();

        foreach ($assets as $asset) {

            $symbol = strtoupper($asset->symbol);

            $this->assetsCache[$symbol] = $asset;
        }


        /*
         * التأكد من وجود أصول
         */
        if (empty($this->assetsCache)) {

            $this->error('⚠️ No active Forex assets found.');

            return Command::FAILURE;
        }


        /*
         * إنشاء قائمة الرموز
         */
        $symbols = implode(',', array_keys($this->assetsCache));


        /*
         * رسالة الاشتراك
         */
        $subscribeMessage = json_encode([
            'action'  => 'subscribe',
            'symbols' => $symbols,
        ]);


        /*
         * WebSocket URL
         */
        $wsUrl = 'wss://ws.eodhistoricaldata.com/ws/forex?api_token=67f4cea78e4f60.22404437';


        $this->info('📊 Symbols loaded: ' . count($this->assetsCache));
        $this->line('📡 Symbols: ' . $symbols);
        $this->line('');


        /*
         * إنشاء Event Loop
         */
        $loop = Factory::create();

        $connector = new Connector($loop);


        /*
         * بدء الاتصال
         */
        $this->startWebSocket(
            $wsUrl,
            $connector,
            $loop,
            $subscribeMessage
        );


        /*
         * تشغيل Event Loop
         */
        $loop->run();

        return Command::SUCCESS;
    }


    /**
     * الاتصال بـ WebSocket
     */
    private function startWebSocket(
        string $wsUrl,
        Connector $connector,
        $loop,
        string $subscribeMessage
    ) {
        $this->info('🔌 Connecting to Forex WebSocket...');


        $connector($wsUrl)->then(

            /*
             * نجح الاتصال
             */
            function (WebSocket $conn) use (
                $wsUrl,
                $connector,
                $loop,
                $subscribeMessage
            ) {

                $this->info('✅ Connected successfully!');
                $this->info('📨 Sending subscription...');

                /*
                 * إرسال الاشتراك
                 */
                $conn->send($subscribeMessage);


                /*
                 * استقبال البيانات
                 */
                $conn->on('message', function ($data) {

                    /*
                     * تحويل JSON إلى Array
                     */
                    $response = json_decode($data, true);


                    /*
                     * في حال كانت البيانات غير صالحة
                     */
                    if (json_last_error() !== JSON_ERROR_NONE) {

                        $this->warn(
                            '⚠️ Invalid JSON received: ' . $data
                        );

                        return;
                    }


                    /*
                     * عرض البيانات الخام
                     */
                    $this->line('');
                    $this->line('------------------------------------------');
                    $this->line('📥 RAW DATA: ' . $data);


                    /*
                     * التأكد من وجود البيانات المطلوبة
                     */
                    if (
                        !isset($response['s']) ||
                        !isset($response['b']) ||
                        !isset($response['a'])
                    ) {

                        $this->warn(
                            '⚠️ Message does not contain symbol/bid/ask'
                        );

                        return;
                    }


                    /*
                     * استخراج البيانات
                     */
                    $symbol = strtoupper($response['s']);

                    $bidPrice = $response['b'];

                    $askPrice = $response['a'];


                    /*
                     * عرض الأسعار القادمة
                     */
                    $this->info(
                        "📈 {$symbol} | BID: {$bidPrice} | ASK: {$askPrice}"
                    );


                    /*
                     * تنسيق السعر إلى 5 منازل عشرية
                     * إذا كان يحتوي على أكثر من 5
                     */
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


                    /*
                     * البحث عن الأصل في RAM
                     */
                    if (!isset($this->assetsCache[$symbol])) {

                        $this->warn(
                            "⚠️ {$symbol} received but not found in assetsCache"
                        );

                        return;
                    }


                    /*
                     * الحصول على Asset من الذاكرة
                     */
                    $asset = $this->assetsCache[$symbol];


                    /*
                     * إذا لم يتغير السعر
                     */
                    if (
                        (string) $asset->bid_price === (string) $bidPrice &&
                        (string) $asset->ask_price === (string) $askPrice
                    ) {

                        $this->line(
                            "➖ NO CHANGE: {$symbol}"
                        );

                        return;
                    }


                    /*
                     * حفظ الأسعار القديمة
                     */
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
                     * تحديث النسخة الموجودة في RAM
                     */
                    $asset->bid_price = $bidPrice;

                    $asset->ask_price = $askPrice;


                    /*
                     * طباعة نتيجة التحديث
                     */
                    $this->info(
                        "💾 UPDATED {$symbol}"
                    );

                    $this->line(
                        "   BID: {$oldBid} → {$bidPrice}"
                    );

                    $this->line(
                        "   ASK: {$oldAsk} → {$askPrice}"
                    );

                    $this->line('------------------------------------------');
                });


                /*
                 * عند حدوث خطأ في الاتصال
                 */
                $conn->on('error', function ($e) {

                    $this->error(
                        '❌ WebSocket Error: ' . $e->getMessage()
                    );

                    \Log::error(
                        'Forex WebSocket Error: ' . $e->getMessage()
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
                            "⚠️ WebSocket closed. Code: {$code}, Reason: {$reason}"
                        );

                        $this->warn(
                            '🔄 Reconnecting in 2 seconds...'
                        );


                        /*
                         * إعادة الاتصال بعد ثانيتين
                         * بدون sleep()
                         */
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
             * فشل الاتصال
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
                    '🔄 Retrying connection in 5 seconds...'
                );


                /*
                 * إعادة المحاولة بدون إيقاف الـ Event Loop
                 */
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
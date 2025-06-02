<?php

namespace App\Console\Commands;

use App\Http\Controllers\MainTPController;
use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Asset;
use App\Models\Order;
use Carbon\Carbon;

class CalculatePnl extends Command
{
    protected $signature = 'calculate:pnl';

    protected $description = 'Calculate PnL for open orders';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $loop = true;
        while ($loop) {
            $orders = Order::whereNull('closed_at')->orderBy('pnl')->get();
            foreach ($orders as $order) {
                $this->calculate_pnl($order);
            }
        }
    }

    public function calculate_pnl($order)
    {
        $client = $order->client;
        $asset = Asset::find($order->currency);
        if ($order->status == 'active') {
            if ($order->type == 1) {
                $currentPrice = $asset->bid_price;
            }else{
                $currentPrice = $asset->ask_price;
            }
            if (!str_starts_with($order->asset->symbol, 'USD') && $order->ref_currency == 'USD') {
                if ($order->type == 1) {
                    $def_price = $currentPrice - $order->open_price;
                }else{
                    $def_price = $order->open_price - $currentPrice;
                }
                $pnl = $order->amount * $asset->size[$client->asset_group_id] * $def_price;
            }
            else{
                $pipSize = $order->amount;
                
                if ($order->type == 1) {
                    $pipDiff = ($currentPrice - $order->open_price) / $pipSize;
                } else {
                    $pipDiff = ($order->open_price - $currentPrice) / $pipSize;
                }
                
                $standardLot = $asset->size[$client->asset_group_id];
                $pipValueJPY = $standardLot * $pipSize;
                
                $pipValueStandard = $pipValueJPY / $currentPrice;
                
                $pipValue = $pipValueStandard * $order->amount;
                
                $pnl = $pipDiff * $pipValue;
                
            }
            if (($order->pnl != $pnl || !$order->pnl) || ($order->close_price != $currentPrice || !$order->close_price)) {
                $order->update([
                    'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                    'close_price' => $currentPrice,
                ]);
                if ($order->s_p && (float)$order->pnl >= (float)$order->s_p) {
                    $order->update([
                        'closed_at' => Carbon::now(),
                        'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                    ]);
                    Notification::create([
                        'client_id' => $order->client->id,
                        'text'      => 'order_closedtp_notification',
                    ]);
                }
                if ($order->s_l && (float)$order->pnl <= -((float)$order->s_l)) {
                    $order->update([
                        'closed_at' => Carbon::now(),
                        'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                    ]);
                    Notification::create([
                        'client_id' => $order->client->id,
                        'text'      => 'order_closedsl_notification',
                    ]);
                }
            }
        }
        if ($order->status != 'active') {
            if ($order->open_at_price >= $asset->ask_price && $order->type == 1) {
                $order->update([
                    'status' => 'active',
                ]);
                Notification::create([
                    'client_id' => $order->client->id,
                    'text'      => 'order_opened_notification',
                ]);
            }
            if ($order->open_at_price >= $asset->bid_price && $order->type == 2) {
                $order->update([
                    'status' => 'active',
                ]);
                Notification::create([
                    'client_id' => $order->client->id,
                    'text'      => 'order_opened_notification',
                ]);
            }
        }

        if (!isset($client->options['ignoreLiquidation']) && $client->source == 'BNC') {
            $finance = (new MainTPController)->get_financial_data($client->broker_id,1);
            if ($finance['equity'] < 0) {
                foreach ($client->orders as $order) {
                    $order->update([
                        'closed_at' => now(),
                    ]);
                }
            }
        }
    }
}

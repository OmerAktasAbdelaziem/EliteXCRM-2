<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Services
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
use App\Models\Asset;

class OrderApiController extends Controller
{
    protected $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

  
    public function getFinancialData(Request $request)
    {
        $request->validate([
            'broker_id' => 'required|integer',
        ]);

        $brokerId = $request->broker_id;

        $finance = $this->orderService->getFinancialData($brokerId);

        return response()->json([
            'success' => true,
            'broker_id' => $brokerId,
            'finance' => $finance,
        ]);
    }

    public function calculatePnlWithoutOrder(Request $request)
    {
        $request->validate([
            'asset' => 'required|integer',
            'orderType' => 'required|integer',
            'openPrice' => 'required|float',
            'currentPrice' => 'required|float',
            'amount' => 'required|float',
            'clientId' => 'required|integer',
        ]);

        $asset = $request->asset;
        $orderType = $request->orderType;
        $openPrice = $request->openPrice;
        $currentPrice = $request->currentPrice;
        $amount = $request->amount;
        $clientId = $request->clientId;

        $pnl = $this->orderService->calculatePnlWithoutOrder($clientId,$currentPrice, $asset ,$amount,$openPrice,$type);

        return response()->json([
            'success' => true,
            'pnl' => $pnl,
        ]);
    }


    public function getRequiredMargin(Request $request, Asset $asset)
    {
        $request->validate([
            'amount'        => 'required|numeric',
            'open_price'    => 'required|numeric',
        ]);

        $size = $asset?->groupAssignments?->first()?->size;
        $leverage = $asset?->groupAssignments?->first()?->leverage;
        if ($size === null || $leverage === null) {
            return response()->json([
                'success' => false,
                'message' => 'Order currency had been removed from asset group assigned to user, please choose another currency for the order.'
            ]);
        }

        if (str_starts_with($asset->symbol, 'USD') || (!strpos($asset->symbol, 'USD') && $asset->currency !== "USD")) {
            $reqMargin = (($request->amount * $request->open_price * $size) / $leverage) * (1 / $request->open_price);
        } else {
            $reqMargin = (($request->amount * $request->open_price * $size) / $leverage) * (1 / $request->open_price);
        }
        if (($asset->groupAssignments->first()->is_percentage ?? 0) == 1) {
            $reqMargin = ($request->amount * $request->open_price * $size) / $leverage;
        }

        return response()->json([
            'success' => true,
            'required_margin' => $reqMargin
        ]);
    }
    
}
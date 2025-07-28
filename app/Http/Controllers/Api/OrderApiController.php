<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Services
use App\Http\Services\Order\Interfaces\OrderServiceInterface;

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

    
}
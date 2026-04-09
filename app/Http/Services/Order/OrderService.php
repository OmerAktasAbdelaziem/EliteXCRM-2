<?php

namespace App\Http\Services\Order;



//Interfaces
use App\Http\Repositories\Order\Interfaces\OrderRepositoryInterface;
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\Asset\Interfaces\AssetServiceInterface;
use App\Http\Services\Order\Interfaces\MoneyTransactionServiceInterface;
use App\Models\Notification;
//Models 
use App\Models\Order;

use Illuminate\Support\Facades\DB;

//Other
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class OrderService implements OrderServiceInterface {

    protected $orderRepository;
    protected $clientService;
    protected $assetService;
    protected $moneyTransactionService;

    public function __construct(OrderRepositoryInterface $orderRepository,
            ClientServiceInterface $clientService,
            AssetServiceInterface $assetService,
            MoneyTransactionServiceInterface $moneyTransactionService,
            ) {
        $this->orderRepository = $orderRepository;
        $this->clientService = $clientService;
        $this->assetService = $assetService;
        $this->moneyTransactionService = $moneyTransactionService;
    }
    public function getAll(): Collection{
        $results = $this->orderRepository->getAll();
        return $results;
    }

    public function getById(int $id): Collection{
        $results = $this->orderRepository->getById($id);
        return $results;
    }
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->orderRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->orderRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->orderRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->orderRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->orderRepository->createBulk($data);
    }

    public function deleteByParams(array $Ids): int {
        return $this->orderRepository->deleteByIDs($Ids);
    }
    
    public function calculatePnl(Order $order,int $commands = 0):int{
       // $commands = 1;
      // $order = Order::find(86);
        $client = $order->client;
        $asset  = $this->assetService->getById($order->currency)->first();

        $groupId = $client->asset_group_id;
       /* $asset->load(['groupAssignments' => function($query) use ($groupId) {
            $query->where('asset_group', $groupId);  
        }]);*/

       // $assetGroupAssignment = $asset->groupAssignments->first();
       $assetGroupAssignment = DB::table('asset_group_assignments')
    ->where('asset', $order->currency)
    ->where('asset_group', $groupId)
    ->get();

        if ($order->status == 'active') {
            if(!$commands){
                $currentPrice = $order->close_price;
            }else{
                if ($order->type == 1) {
                    $currentPrice = $asset->bid_price;
                }else{
                    $currentPrice = $asset->ask_price;
                }
            }
            
            $pnl = $this->pnLCalculation($order, $currentPrice, $assetGroupAssignment,$asset);
            
            if (($order->pnl != $pnl || !$order->pnl) || ($order->close_price != $currentPrice || !$order->close_price)) {
                $this->update($order->id,[
                    'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                    'close_price' => $currentPrice,
                ]);
                if($commands){
                    if ($order->type == 1) {
                        if ($order->s_p && (float)$currentPrice >= (float)$order->s_p) {
                            $this->update($order->id,[
                                'closed_at' => Carbon::now(),
                                'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                            ]);
                            Notification::create([
                                'client_id' => $order->client->id,
                                'text'      => 'order_closedtp_notification',
                            ]);
                        }
                        if ($order->s_l && (float)$currentPrice <= (float)$order->s_l) {
                            $this->update($order->id, [
                                'closed_at' => Carbon::now(),
                                'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                            ]);
                            Notification::create([
                                'client_id' => $order->client->id,
                                'text'      => 'order_closedsl_notification',
                            ]);
                        }
                    } else {
                        if ($order->s_p && (float)$currentPrice <= (float)$order->s_p) {
                            $this->update($order->id,[
                                'closed_at' => Carbon::now(),
                                'pnl' => rtrim(rtrim(sprintf('%f', $pnl), '0'), '.'),
                            ]);
                            Notification::create([
                                'client_id' => $order->client->id,
                                'text'      => 'order_closedtp_notification',
                            ]);
                        }
                        if ($order->s_l && (float)$currentPrice >= (float)$order->s_l) {
                            $this->update($order->id, [
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
            }
        }
        if($commands){
        if ($order->status != 'active') {
            if ($order->open_at_price >= $asset->ask_price && $order->type == 1) {
                $this->update($order->id, [
                    'status' => 'active',
                ]);
                Notification::create([
                    'client_id' => $order->client->id,
                    'text'      => 'order_opened_notification',
                ]);
            }
            if ($order->open_at_price >= $asset->bid_price && $order->type == 2) {
                $this->update($order->id, [
                    'status' => 'active',
                ]);
                Notification::create([
                    'client_id' => $order->client->id,
                    'text'      => 'order_opened_notification',
                ]);
            }
        }

        if (!isset($client->options['ignoreLiquidation']) && $client->source == 'BNC') {
            $finance = $this->getFinancialData($order->broker_id);//(new MainTPController)->get_financial_data($client->broker_id,1);
            if ($finance['equity'] < 0) {
                foreach ($client->orders as $order) {
                  $this->update($order->id, [
                    'closed_at' => Carbon::now(),
                ]);
                }
            }
        }
        }
        return 1;
    }
    
    public function calculatePnlWithoutOrder(int $clientId,float $currentPrice, $asset, $amount, $openPrice, $type): float
    {
        $asset  = $this->assetService->getById($asset)->first();

        $client = $this->clientService->getById($clientId)->first();
       
        
        //dd($clientId);
        $groupId = $client->asset_group_id;

        
       // dd($groupId);
        /*$asset->load(['groupAssignments' => function($query) use ($groupId) {
            $query->where('asset_group', $groupId);  
        }]);*/



        //$assetGroupAssignment = $asset->groupAssignments->first();

        $assetGroupAssignment = DB::table('asset_group_assignments')
    ->where('asset', $asset->id)
    ->where('asset_group', $groupId)
    ->get();


        //dd($assetGroupAssignment);

        $pnl = $this->pnLCalculation(null, $currentPrice,$asset, $assetGroupAssignment, $amount, $openPrice, $type);
        return $pnl;
    }

    public function pnLCalculation($order = null, float $currentPrice,$asset, $assetGroupAssignment, $amount = null,$openPrice = null, $type = null): float
    {
        $type = $order?->type ?? $type;
        $openPrice = $order?->open_price ?? $openPrice;
        $amount = $order?->amount ?? $amount;
        if (($order == null && !str_starts_with($asset->symbol, 'USD')) || ($order != null &&
            !str_starts_with($order->asset->symbol, 'USD') &&
            $order->ref_currency === 'USD') 
        ) {
            // Direct calculation
            if ($type == 1) { // Buy
                $def_price = $currentPrice - $openPrice;
            } else { // Sell
                $def_price = $openPrice - $currentPrice;
            }
    //dd($assetGroupAssignment->size);
            $pnl = $amount * $assetGroupAssignment->size * $def_price;
            return $pnl;
        } else {
            // Pip-based calculation
            $pipSize = $amount;
    
            if ($type == 1) { // Buy
                $pipDiff = ($currentPrice - $openPrice) / $pipSize;
            } else { // Sell
                $pipDiff = ($openPrice - $currentPrice) / $pipSize;
            }
    
            $standardLot = $assetGroupAssignment?->size;
            
            if ($standardLot === null) {
                return 0.0;
            }
    
            $pipValueJPY = $standardLot * $pipSize;
            $pipValueStandard = $pipValueJPY / $currentPrice;
            $pipValue = $pipValueStandard * $amount;
    
            $pnl = $pipDiff * $pipValue;
           // echo $pipValueStandard .'*'. $currentPrice;

            return $pnl;
        }
    }



    public function getClosedOrdersPL(int $brokerId):float
    {
        return $this->orderRepository->getClosedOrdersPL($brokerId);
    }
    
    public function getFinancialData(int $brokerId): array
    {
        $finance = [];
        $finance['last_deposit_amount'] = 0.00;
        $finance['totalWithdrawal']     = 0.00;
        $finance['totalDeposit']        = 0.00;
        $finance['ftd_amount']          = 0.00;
        $finance['usedMargin']          = 0.00;
        $finance['currentPL']           = 0.00;
        $finance['balance']             = 0.00;
        $finance['credit']              = 0.00;
        $finance['equity']              = 0.00;
        $finance['bonus']               = 0.00;
        $finance['freeMargin']          = 0.00;
    
        //$client = Client::where('broker_id', $broker_id)->where('deleted', 0)->first();
        $client = $this->clientService->getByFilters([
    ['field' => 'broker_id', 'conditions' => ['=' => $brokerId]],
    ['field' => 'deleted',   'conditions' => ['=' => 0]],
])->first();
        if ($client) {
            $finance['totalDeposit'] = $this->moneyTransactionService->getDeposits($brokerId);
            $lastDeposit = $this->moneyTransactionService->getLastDeposit($brokerId)->first();
            $finance['last_deposit_amount'] = $lastDeposit ? $lastDeposit->amount : 0.00;
            $finance['ftd_amount'] = $finance['last_deposit_amount'];
            $finance['totalWithdrawal'] = $this->moneyTransactionService->getWithdrawals($brokerId);
            $openedOrders = $this->getByFilters([
    ['field' => 'broker_id', 'conditions' => ['=' => $brokerId]],
    ['field' => 'closed_at', 'conditions' => ['null' => true]],
]);
            $finance['usedMargin'] = $openedOrders->sum('required_margin');
            $finance['currentPL'] = $openedOrders->sum('pnl');
            $closedOrdersPL = $this->getClosedOrdersPL($brokerId);
            $finance['closedOrdersPL'] = $closedOrdersPL;
            $creditIn = $this->moneyTransactionService->getCreditIn($brokerId);
            $creditOut = $this->moneyTransactionService->getCreditOut($brokerId);
            $finance['credit'] = $creditIn - $creditOut;
            $finance['balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']) + $closedOrdersPL + $finance['credit'] ;
            $finance['withdraw_balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']);
            $finance['pendingWithdrawal'] = $this->moneyTransactionService->getPendingWithdrawal($brokerId);
            $bonusIn = $this->moneyTransactionService->getBonusIn($brokerId);
            $bonusOut = $this->moneyTransactionService->getBonusOut($brokerId);
            $finance['bonus'] = $bonusIn - $bonusOut;
            $finance['equity'] = $finance['balance'] + $finance['currentPL'] +  $finance['bonus'];
            $finance['freeMargin'] = ($finance['balance'] - $finance['usedMargin']) + $finance['bonus'];
        }
        return $finance;
    }
    
}
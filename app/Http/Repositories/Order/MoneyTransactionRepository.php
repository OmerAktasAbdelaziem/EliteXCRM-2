<?php
namespace App\Http\Repositories\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\MoneyTransactionRepositoryInterface;
//Services
use App\Http\Services\Filter\Interfaces\FilterServiceInterface;
//Models
use App\Models\MoneyTrx;
use App\Models\MoneyTrxDetail;
//Other
use Illuminate\Database\Eloquent\Collection;

class MoneyTransactionRepository implements MoneyTransactionRepositoryInterface {

    protected FilterServiceInterface $filterService;

    public function __construct(FilterServiceInterface $filterService) {
        $this->filterService = $filterService;
    }
    
    public function getAll(): Collection
{
    return MoneyTrx::all();
}

public function getById(int $id): Collection {
    $item = MoneyTrx::where('id',$id)->get();
    return $item;
}

    public function getByFilters(array $params, array $with = []): Collection {
       
        
        $query = MoneyTrx::query();
if (!empty($with)) {
        $query->with($with);
    }
    $filteredQuery = $this->filterService->applyFilters($query, $params);

    
    return $filteredQuery->get();
    }
  
  public function create(array $data): Collection
    {
      //this code will be used later to create with details
//      return DB::transaction(function () use ($trxData, $details) {
//        $transaction = MoneyTrx::create($trxData);
//
//        foreach ($details as $detail) {
//            $transaction->details()->create($detail);
//        }
//
//        return $transaction;
//    });
      
      
        $result = MoneyTrx::create($data);
        return new Collection([$result]);
    }
    
    public function update(int $id,array $data): int
    {
        return $result = MoneyTrx::where('id', $id)->update($data);
        
    }
    public function updateBulk(array $ids,array $data): int
    {
        return $result = MoneyTrx::whereIn('id', $ids)->update($data);
        
    }
    
    public function createBulk(array $data): bool 
    {
        return MoneyTrx::insert($data);
    }


    public function deleteByParams(array $params): int 
    {
        return MoneyTrx::where(function ($query) use ($params) {
    foreach ($params as $key => $value) {
        if(is_array($value)){
        $query->whereIn($key, $value);    
        }else{
        $query->where($key, $value);
        }
    }
})->delete();
    }
  
  public function getDeposits(int $brokerId):float{
      /*$deposits = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'deposit')
    ->sum('money_trx_details.amount');*/
      $deposits = MoneyTrxDetail::where('type', 'deposit')
    ->whereHas('moneyTrx', function ($q) use ($brokerId) {
        $q->where('broker_id', $brokerId)
          ->where('status', 'accepted');
    })
    ->sum('amount');
    return $deposits;
  }
  
  public function getLastDeposit(int $brokerId):Collection
  {
      /*
       $lastDeposit = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'deposit')
    ->orderBy('money_trxes.created_at', 'desc')
    ->first();
       */
      $lastDeposit = MoneyTrxDetail::where('type', 'deposit')
    ->whereHas('moneyTrx', function ($q) use ($brokerId) {
        $q->where('broker_id', $brokerId)
          ->where('status', 'accepted');
    })
    ->orderByDesc('id')
    ->get();
    return $lastDeposit;
  }
  public function getWithdrawals(int $brokerID):float
  {
      /*
       $withdrawals = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'withdraw')
    ->sum('money_trx_details.amount');
       */
      
      $withdrawals = MoneyTrxDetail::where('type', 'withdraw')
    ->whereHas('moneyTrx', function ($q) use ($brokerID) {
        $q->where('broker_id', $brokerID)
          ->where('status', 'accepted');
    })
    ->sum('amount');
    return $withdrawals;
  }
  
  


  public function getCreditIn(int $brokerId):float
  {
      /*
       $creditIn = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'credit in')
    ->sum('money_trx_details.amount');
       */
      $creditIn = MoneyTrxDetail::where('type', 'credit in')
    ->whereHas('moneyTrx', function ($q) use ($brokerId) {
        $q->where('broker_id', $brokerId)
          ->where('status', 'accepted');
    })
    ->sum('amount');
    return $creditIn;
  }
  public function getCreditOut(int $brokerId):float
  {
      /*
       $creditIn = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'credit out')
    ->sum('money_trx_details.amount');
       */
      $creditOut = MoneyTrxDetail::where('type', 'credit out')
    ->whereHas('moneyTrx', function ($q) use ($brokerId) {
        $q->where('broker_id', $brokerId)
          ->where('status', 'accepted');
    })
    ->sum('amount');
    return $creditOut;
  }
  
  public function getBonusIn(int $brokerId):float
  {
      /*
       $creditIn = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'bonus in')
    ->sum('money_trx_details.amount');
       */
      $bonusIn = MoneyTrxDetail::where('type', 'bonus in')
    ->whereHas('moneyTrx', function ($q) use ($brokerId) {
        $q->where('broker_id', $brokerId)
          ->where('status', 'accepted');
    })
    ->sum('amount');
    return $bonusIn;
  }
  
  public function getBonusOut(int $brokerId):float
  {
      /*
       $creditIn = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->where('money_trx_details.type', 'bonus in')
    ->sum('money_trx_details.amount');
       */
      $bonusOut = MoneyTrxDetail::where('type', 'bonus out')
    ->whereHas('moneyTrx', function ($q) use ($brokerId) {
        $q->where('broker_id', $brokerId)
          ->where('status', 'accepted');
    })
    ->sum('amount');
    return $bonusOut;
  }
  
  public function getPendingWithdrawal(int $brokerId):float
  {
  $pendingWithdrawal = MoneyTrx::where('broker_id',$brokerId)
                                               ->where('type','withdraw')
                                               ->where('status','pending')
                                               ->sum('amount');
  
  return $pendingWithdrawal;
  }
  
}
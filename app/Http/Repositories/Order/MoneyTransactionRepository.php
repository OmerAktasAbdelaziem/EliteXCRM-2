<?php
namespace App\Http\Repositories\Order;

//Interfaces
use App\Http\Repositories\Order\Interfaces\MoneyTransactionInterface;
//Models
use App\Models\MoneyTrx;
use App\Models\MoneyTrxDetail;
//Other
use Illuminate\Database\Eloquent\Collection;

class MoneyTransactionRepository implements MoneyTransactionInterface {

    public function getByParams(array $params): ?Collection{
      $items = MoneyTrx::where(function ($query) use ($params) {
    foreach ($params as $key => $value) {
        if(is_array($value)){
        $query->whereIn($key, $value);    
        }else{
        $query->where($key, $value);
        }
    }
})->get();
return $items->isEmpty() ? null : $items;
      
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
  
  
  
}
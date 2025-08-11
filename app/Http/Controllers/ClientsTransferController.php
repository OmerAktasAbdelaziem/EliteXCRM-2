<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\SmartUser;
use App\Models\SmartUserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MikeMcLin\WpPassword\Facades\WpPassword;
use App\Models\Action;
use App\Models\ArkAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Order\Interfaces\MoneyTransactionServiceInterface;

class ClientsTransferController extends Controller
{
    protected $moneyTransactionService;
    public function __construct(
            MoneyTransactionServiceInterface $moneyTransactionService,
            ) {
        $this->moneyTransactionService = $moneyTransactionService;
        
    }
    public function register_user(Request $request)
    {
        sleep(10);

        $inputs = $request->only([
            'smart_user_id',
        ]);

        $old_client = Client::where('email',$request->email)->first();

        if ($old_client != null && !$old_client->smart_user_id) {
            $old_client->update($inputs);
        }else{
            $first_name = SmartUserMeta::where('user_id', $request->smart_user_id)
                ->where('meta_key', 'first_name')
                ->value('meta_value');

            $last_name = SmartUserMeta::where('user_id', $request->smart_user_id)
                ->where('meta_key', 'last_name')
                ->value('meta_value');

            $phone = SmartUserMeta::where('user_id', $request->smart_user_id)
                ->where('meta_key', 'user_registration_phone_number')
                ->value('meta_value');

            $country = SmartUserMeta::where('user_id', $request->smart_user_id)
                ->where('meta_key', 'user_registration_country')
                ->value('meta_value');

            $inputs = array_merge($inputs, [
                'sales_status' => 'New',
                'created_by'   => 'phooenixs.com',
                'first_name'   => $first_name,
                'last_name'    => $last_name,
                'country'      => $country,
                'phone1'       => $phone,
                'email'        => $request->email,
            ]);

            Client::create($inputs);
        }

        Log::channel('telegram')->info('New smart Reg: '.$request->email.' ID: '.$request->smart_user_id);
    }

        function demo(Request $request, $id) {
        $client = Client::findOrFail($id);
    
        $old_client = Client::whereNotNull('broker_id')->where(function ($query) use ($request) {
            $query->where('username', $request->username);
        })->get();
    
        if ($old_client->count() > 0) {
            return redirect()->back()->with('fail', 'Username is already exist');
        }
    //gohere
        //die('b');
        $broker_id = $this->createAccountFromApp('true', $request->password, $request->username, $client);
        if ($broker_id == false) {
            return redirect()->back()->with('fail', 'Could not create account');
        }
    
        $password = Hash::make($request->password);
        $options = [];
        $options['enableWithdrawalRequest'] = 1;
        $options['enableDepositRequest'] = 1;
        $options['isEnabled'] = 1;
        if ($request->forceChangePassword) {
            $options['forceChangePassword'] = 1;
        }
    
        $client->update([
            'favourite_assets' => ["1", "2", "3", "4", "5", "6", "20", "22", "10", "73", "74"],
            'asset_group_id' => 1,
            'password_text' => $request->password,
            'account_type' => 'Demo',
            'broker_id' => $broker_id,
            'username' => $request->username,
            'password' => $password,
            'options' => $options,
        ]);
    
        if (!$client->reg_date) {
            $client->update([
                'reg_date' => Carbon::now()
            ]);
        }
    
        $moneyTrx = [];
        $moneyTrx['broker_id'] = $broker_id;
        $moneyTrx['type'] = 'deposit';
        $moneyTrx['status'] = 'accepted';
        $moneyTrx['amount'] = $request->amount;
        $this->moneyTransactionService->create($moneyTrx);
        
        $action = Action::create([
            'client_id' => $id,
            'user_id' => Auth::id(),
            'text' => 'Opened <span class="text-primary"> Demo Account with ' . $request->amount . ' amount </span>'
        ]);
    
        Log::channel('telegram')->info(Auth::user()->username . ' ' . $action->text . ' On ' . $client->first_name . ' ' . $client->last_name);
    
        return redirect()->back()->with('success', 'Demo has been opened successfully');
    }


    function real(Request $request, $id) {
        $client    = Client::findOrFail($id);
        $old_client = Client::whereNotNull('broker_id')->where(function ($query) use ($request, $client) {
            $query->where('username', $request->username);
        })->get();

        if ($old_client->count() > 0) {
            return redirect()->back()->with('fail','Username is already exist'); 
        }

        $broker_id = $this->createAccountFromApp('false',$request->password,$request->username,$client);//die('a');
        if ($broker_id == false) {
            return redirect()->back()->with('fail','Couldnt Create Real Account');
        }

        $password  = Hash::make($request->password);
        $options   = [];
        $options['enableWithdrawalRequest'] = 1;
        $options['enableDepositRequest'] = 1;
        $options['isEnabled'] = 1;
        if ($request->forceChangePassword) {
            $options['forceChangePassword'] = 1;
        }

        $client->update([
            'favourite_assets' => ["1","2","3","4","5","6","20","22","10","73","74"],
            'asset_group_id'   => 1,
            'password_text'    => $request->password,
            'account_type'     => 'Real',
            'broker_id'        => $broker_id,
            'username'         => $request->username,
            'password'         => $password,
            'options'          => $options,
        ]);

        if (!$client->reg_date) {
            $client->update([
                'reg_date' => Carbon::now()
            ]);
        }

        $action = Action::create([
            'client_id' => $id,
            'user_id'   => Auth::id(),
            'text'      => 'Opened <span class="text-primary"> Real Account </span>'
        ]);

        Log::channel('telegram')->info(Auth::user()->username.' '.$action->text.' On '.$action->client->first_name.' '.$action->client->last_name);

        return redirect()->back()->with('success','Real account has been opened successfully');
    }


   public function createAccountFromApp($isDemo, $password, $username, $clientData)
    {
       if(isset($clientData['id'])){
        $existingClient = Client::where('id', $clientData['id'])->first();   
       }else{
        $existingClient = Client::where('email', $clientData['email'])->first();
       }
    //print_r();die;
        if ($existingClient) {
            if ($existingClient->broker_id) {
                return $existingClient->broker_id;
            } else {
                $lastBrokerId = Client::max('broker_id');
                $newBrokerId = $lastBrokerId ? $lastBrokerId + 1 : 1000;
    
                $existingClient->broker_id = $newBrokerId;
                $existingClient->username = $username;
                $existingClient->password = Hash::make($password);
                $existingClient->account_type = ($isDemo === 'true') ? 'Demo' : 'Real';
                $existingClient->updated_at = now();
                $existingClient->save();
    
                return $newBrokerId;
            }
        }
    
        $lastBrokerId = Client::max('broker_id');
        $newBrokerId = $lastBrokerId ? $lastBrokerId + 1 : 1000;
    
        $client = new Client();
        $client->username = $username;
        $client->password = Hash::make($password);
        $client->account_type = ($isDemo === 'true') ? 'Demo' : 'Real';
        $client->email = $clientData['email'];
        $client->broker_id = $newBrokerId;
        $client->created_at = now();
        $client->updated_at = now();
        $client->save();
    
        return $newBrokerId;
    }


}

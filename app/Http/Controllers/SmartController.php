<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Client;
use App\Models\Client_comment;
use App\Models\EmailLog;
use App\Models\SmartUser;
use App\Models\SmartUserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use MikeMcLin\WpPassword\Facades\WpPassword;

class SmartController extends Controller
{
    public function show($id,Request $request)
    {
        $mainTpController = new MainTPController();
        $limit            = $request->input('limit', 6);
        $tab              = $request->input('tab', 'info');
        $client           = Client::findOrfail($id);
        $comments         = Client_comment::where('client_id',$id)->latest()->get();
        $email_logs       = EmailLog::where('client_id',$id)->where('type','!=','Demo')->where('type','!=','real')->latest()->limit(6)->get();
        $online           = $mainTpController->get_online_status($client->broker_id);
        $smartClient      = SmartUser::where('ID',$client->smart_user_id)->first();

        if (!$smartClient) {
            $client->update(['smart_user_id'=>null,'smart_data'=>'']);
            session()->flash('fail', "SmartClient Account not found !");

            return response()->json([
                'success' => true,
            ]);
        }

        $this->update_user($client,$smartClient);

        if (is_string($client->smart_data)) {
            $client->smart_data = json_decode($client->smart_data, true);
        }
        
        return view('client.smart',compact(
            'email_logs',
            'comments',
            'online',
            'client',
            'tab',
        ));
    }

    public function update(Request $request, $id)
    {
        $client      = Client::findOrfail($id);
        $smartClient = SmartUser::find($client->smart_user_id);

        $request->validate([
            'smart_data.first_name' => ['required' , 'string'],
            'smart_data.password'   => ['required'],
        ]);

        $existUser = SmartUser::where(function ($query) use ($request, $client) {
            $query->where('user_login', $request->smart_data['username'])->orWhere('user_email', $request->smart_data['email']);
        })->where('ID', '!=', $client->smart_user_id)->first();

        if ($client->smart_user_id) {
            $users = Cache::remember('wp_users_all', 60, function () {
                return SmartUser::all();
            });
        }
        
        if ($existUser) {
            if ($existUser->user_login === $request->smart_data['username']) {
                session()->flash('fail', "This username is already exist !");
            } elseif ($existUser->user_email === $request->smart_data['email']) {
                session()->flash('fail', "This Email is already exist !");
            }
        
            return response()->json([
                'success' => true,
            ]);
        }

        $inputs = $request->only([
            'smart_data',
        ]);

        $fieldsToCheck = [
            'first_name',
            'last_name',
            'username',
            'password',
            'country',
            'phone1',
            'phone2',
            'amount',
            'bonus',
            'email',
        ];

        if (is_string($client->smart_data)) {
            $client->smart_data = json_decode($client->smart_data, true);
        }
        
        if (is_null($client->smart_data)) {
            $client->smart_data = [];
        }
        
        foreach ($fieldsToCheck as $field) {
            if ((isset($request->smart_data[$field]) && isset($client->smart_data[$field]) && $client->smart_data[$field] != $request->smart_data[$field]) || !isset($client->smart_data[$field])) {
                $action = Action::create([
                    'client_id' => $id,
                    'user_id'   => Auth::id(),
                    'text' => 'Updated smart client <strong>' . ucfirst(str_replace('_', ' ', $field)) . '</strong> From <span class="text-danger">' . 
                    (isset($client->smart_data[$field]) ? $client->smart_data[$field] : '') . 
                    '</span> To <span class="text-primary">' . 
                    (isset($request->smart_data[$field]) ? $request->smart_data[$field] : '') . 
                    '</span>'
                ]);

                if ($field == 'email') {
                    Log::channel('telegram')->info(Auth::user()->username.' '.$action->text.' On '.$action->client->first_name.' '.$action->client->last_name);
                }
            }

            if ((!isset($request->smart_data[$field]) || empty($inputs['smart_data'][$field]) || !$inputs['smart_data'][$field]) && isset($client->smart_data[$field])) {
                $inputs['smart_data'][$field] = $client->smart_data[$field];
            }

        }

        $smartInputs = [
            'display_name'  => $inputs['smart_data']['first_name'].' '.$inputs['smart_data']['last_name'],
            'user_login'    => $inputs['smart_data']['username'],
            'user_nicename' => $inputs['smart_data']['username'],
            'amount'        => $inputs['smart_data']['amount'],
            'bonus'         => $inputs['smart_data']['bonus'],
            'user_email'    => $inputs['smart_data']['email'],
        ];

        if ($inputs['smart_data']['password'] != '******') {
            if (!WpPassword::check($inputs['smart_data']['password'], $smartClient->user_pass)) {
                $smartInputs = array_merge($smartInputs, [
                    'user_pass' => WpPassword::make($inputs['smart_data']['password']),
                ]);
            }
        }

        $client->update($inputs);


        $smartClient->update($smartInputs);
        
        $metaData = [
            'first_name'   => $inputs['smart_data']['first_name'],
            'last_name'    => $inputs['smart_data']['last_name'],
            'phone_number' => $inputs['smart_data']['phone1'],
            'country'      => $inputs['smart_data']['country'],
        ];
        
        foreach ($metaData as $key => $value) {
            SmartUserMeta::updateOrCreate(
                ['user_id' => $client->smart_user_id, 'meta_key' => $key], 
                ['meta_value' => $value]
            );
        }        

        session()->flash('success', 'Smart Client updated successfully.');

        return response()->json([
            'success' => true,
        ]);
    }

    public function update_user_password(Request $request)
    {
        $client = Client::where('smart_user_id',$request->user_id)->first();

        if (is_string($client->smart_data)) {
            $client->smart_data = json_decode($client->smart_data, true);
        }
        
        if (is_null($client->smart_data)) {
            $client->smart_data = [];
        }

        $client->smart_data['password'] = $request->password;

        $client->update([
            'smart_data' => json_encode($client->smart_data),
        ]);

        Log::channel('telegram')->info($client->smart_user_id.' updated password !');
    }

    public function update_user($client,$smartClient)
    {
        $phone_number = SmartUserMeta::where('user_id', $client->smart_user_id)->where('meta_key', 'phone_number')->value('meta_value');

        $first_name = SmartUserMeta::where('user_id', $client->smart_user_id)->where('meta_key', 'first_name')->value('meta_value');
        
        $last_name = SmartUserMeta::where('user_id', $client->smart_user_id)->where('meta_key', 'last_name')->value('meta_value');
    
        $country = SmartUserMeta::where('user_id', $client->smart_user_id)->where('meta_key', 'country')->value('meta_value');

        if (is_string($client->smart_data)) {
            $smartData = json_decode($client->smart_data, true);
        } else {
            $smartData = $client->smart_data ?? [];
        }

        $smartData['first_name'] = $first_name;
        $smartData['last_name']  = $last_name;
        $smartData['username']   = $smartClient->user_login;
        $smartData['country']    = $country;
        $smartData['amount']     = $smartClient->amount;
        $smartData['phone1']     = $phone_number;
        $smartData['bonus']      = $smartClient->bonus;
        $smartData['email']      = $smartClient->user_email;
        if (isset($smartData['password'])) {
            if (!WpPassword::check($smartData['password'], $smartClient->user_pass)) {
                unset($smartData['password']);
            }
        }
        

        $client->update([
            'smart_data' => json_encode($smartData),
        ]);
    }
}

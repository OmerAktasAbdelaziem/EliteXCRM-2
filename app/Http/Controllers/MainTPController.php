<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Client_comment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\AssetGroup;
use App\Models\EmailLog;
use App\Models\MoneyTrx;
use App\Models\MoneyTrxDetail;
use App\Models\Action;
use App\Models\Client;
use App\Models\Asset;
use App\Models\Order;
use App\Models\Report;
use App\Models\Status;
use App\Models\Bank;
use App\Models\Chat_ah;
use App\Models\ClientDocument;
use App\Models\MoneyHistory;
use App\Models\Notification;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ClosedOrder;
use App\Models\MoneyTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
//Services
use App\Http\Services\Asset\Interfaces\AssetGroupServiceInterface;
use App\Http\Services\Asset\Interfaces\AssetServiceInterface;
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
//use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Facades\UserPermission;

class MainTPController extends Controller {

    protected $assetGroupService;
    protected $assetService;
    protected $orderService;
    protected $clientService;

//protected $userService;

    public function __construct(
            AssetGroupServiceInterface $assetGroupService,
            AssetServiceInterface $assetService,
            OrderServiceInterface $orderService,
            ClientServiceInterface $clientService,
            //UserServiceInterface $userService,
    ) {
        $this->assetGroupService = $assetGroupService;
        $this->assetService = $assetService;
        $this->orderService = $orderService;
        $this->clientService = $clientService;
        //$this->userService = $userService;
    }

    public function show($id, Request $request) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        $formattedNowFromDate = Carbon::now()->subDays(30)->startOfDay()->format('d/m/Y');
        $formattedNowToDate = Carbon::now()->endOfDay()->format('d/m/Y');
        $moneyTrx_fromTo = $request->moneyTrx_fromTo ?? $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $moneyTrx_fromTo = $moneyTrx_fromTo != 'null - null' ? $moneyTrx_fromTo : $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $opened_fromTo = $request->opened_fromTo ?? $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $opened_fromTo = $opened_fromTo != 'null - null' ? $opened_fromTo : $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $closed_fromTo = $request->closed_fromTo ?? $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $closed_fromTo = $closed_fromTo != 'null - null' ? $closed_fromTo : $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $money_history = MoneyHistory::where('client_id', $id);
        $transactions = null;
        $asset_groups = AssetGroup::where('pipeline_id', Auth::user()->pipeline_id)->get();
        $email_logs = EmailLog::where('client_id', $id)->where('type', 'real')->latest()->limit(6)->get();
        $comments = Client_comment::where('client_id', $id)->latest()->get();
        //$options              = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();
        $actions = Action::where('client_id', $id);
        $changes = null;
        $client = Client::findOrfail($id);
        $status = $request->status ?? $client->sales_status;
        $teams = $this->clientService->getTeams(Auth::user()); //(new ClientsController)->getTeams($options);
        $limit = $request->input('limit', 14);
        $users = $this->clientService->getUsers($teams, Auth::user()); //(new ClientsController)->getUsers($teams);
        $parts = $this->clientService->getParts($teams, Auth::user()); //(new ClientsController)->getParts($teams);
        //print_r($teams);die('a');
        $chat = Chat_ah::where('client_id', $id)->latest()->get();
        $page = $request->query('page', 1);
        $from = Carbon::now()->subYears(10)->format('Y-m-d H:i:s');
        $kycs = ClientDocument::where('client_id', $id)->where('type', 'kyc');
        $tab = $request->input('tab', 'info');
        $to = Carbon::now()->format('Y-m-d H:i:s');

        $broker_id = $client->broker_id ?? 0;
        $statuses = Status::where(function ($query) use ($parts) {
                    $first = true;
                    foreach ($parts as $part) {
                        if ($first) {
                            $query->where('part_ids', 'LIKE', '%"' . $part->id . '"%');
                            $first = false;
                        } else {
                            $query->orWhere('part_ids', 'LIKE', '%"' . $part->id . '"%');
                        }
                    }
                })->latest()->get();

        $nextClient = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));

                    //if (isset($options['leads_data_show_unassigned_leads'])) {
                    if ($isSuperAdmin || $isPipelineAdmin ||  UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        $query->orWhere('user_id', null);
                    }
                })->orderBy('created_at', 'desc');

        $preClient = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));

                    //if (isset($options['leads_data_show_unassigned_leads'])) {
                    if ($isSuperAdmin || $isPipelineAdmin ||  UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        $query->orWhere('user_id', null);
                    }
                })->orderBy('created_at', 'asc');

        if ($filters = $request->get('filters', [])) {
            $actions->where(function ($query) use ($filters) {
                $query->where(function ($subquery) use ($filters) {
                    if ($textQuery = Arr::get($filters, 'search_actions')) {
                        $textQuery = strtolower($textQuery);
                        $textQuery = '%' . $textQuery . '%';
                        $subquery->where(DB::raw('LOWER(text)'), 'like', $textQuery)
                                ->orWhere(DB::raw('REPLACE(text,"\n", " ")'), 'like', $textQuery)
                                ->orWhere(DB::raw('REPLACE(text,"\r\n", " ")'), 'like', $textQuery)
                                ->orWhereHas('user', function ($subsubquery) use ($textQuery) {
                                    $subsubquery->where(DB::raw('LOWER(username)'), 'like', $textQuery)
                                            ->orWhere(DB::raw('LOWER(first_name)'), 'like', $textQuery)
                                            ->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                                })
                                ->orWhereHas('client', function ($subsubquery) use ($textQuery) {
                                    $subsubquery->where(DB::raw('LOWER(first_name)'), 'like', $textQuery)
                                            ->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                                })
                                ->orWhere(DB::raw('LOWER(client_id)'), 'like', $textQuery);
                    }
                });
                if ($fromDate = Arr::get($filters, 'fromTo_actions')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '>=', $formattedFromDate);
                    }
                    if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    }
                }
            });

            if ($fromDate = Arr::get($filters, 'fromTo_history')) {
                $dates = preg_split('/\s*-\s*/', trim($fromDate));

                if (isset($dates[0]) && !empty($dates[0])) {
                    $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                    $money_history->where('created_at', '>=', $formattedFromDate);
                }
                if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                    $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                    $money_history->where('created_at', '<=', $formattedToDate);
                } else {
                    $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                    $money_history->where('created_at', '<=', $formattedToDate);
                }
            }

            if ($fromDate = Arr::get($filters, 'fromTo_kyc')) {
                $dates = preg_split('/\s*-\s*/', trim($fromDate));

                if (isset($dates[0]) && !empty($dates[0])) {
                    $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                    $kycs->where('created_at', '>=', $formattedFromDate);
                }
                if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                    $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                    $kycs->where('created_at', '<=', $formattedToDate);
                } else {
                    $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                    $kycs->where('created_at', '<=', $formattedToDate);
                }
            }

            if ($status_kyc = Arr::get($filters, 'status_kyc')) {
                $kycs->where('status', $status_kyc);
            }

            if ($textQuery = Arr::get($filters, 'contacts_history')) {
                $textQuery = strtolower($textQuery);
                $textQuery = '%' . $textQuery . '%';
                $money_history->whereHas('client', function ($subsubquery) use ($textQuery) {
                    $subsubquery->where(DB::raw('LOWER(first_name)'), 'like', $textQuery)->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                });
            }

            if ($client_id = Arr::get($filters, 'lead_id_history')) {
                $money_history->where('client_id', $client_id);
            }

            if ($type = Arr::get($filters, 'type_history')) {
                $money_history->where('type', $type);
            }

            if ($part = Arr::get($filters, 'part_history')) {
                $money_history->where('part', $part);
            }

            if ($operation_id = Arr::get($filters, 'operation_id_history')) {
                $money_history->where('operation_id', $operation_id);
            }

            if ($textQuery = Arr::get($filters, 'action_history')) {
                $textQuery = strtolower($textQuery);
                $textQuery = '%' . $textQuery . '%';
                $money_history->where(function ($query) use ($textQuery) {
                    $query->where(DB::raw('LOWER(text)'), 'like', $textQuery)
                            ->orWhere(DB::raw('REPLACE(text,"\n", " ")'), 'like', $textQuery)
                            ->orWhere(DB::raw('REPLACE(text,"\r\n", " ")'), 'like', $textQuery);
                });
            }
        }

        $nextClient->where('sales_status', $status);
        $preClient->where('sales_status', $status);

        $nextClient = $nextClient->where('created_at', '<', $client->created_at)->first();

        $preClient = $preClient->where('created_at', '>', $client->created_at)->first();

        $next = $nextClient ? 1 : 0;
        $pre = $preClient ? 1 : 0;

        $moneytrx_request_data = MoneyTrx::where('broker_id', $broker_id)->where('status', 'pending')->get();
        $money_trx_data = $this->get_trx_data($broker_id, $moneyTrx_fromTo);
        $scripts_data = $this->get_scripts_data($client->id);
        //print_r($scripts_data);die('aaa');
        $opened_data = $this->get_opened_data($broker_id, $opened_fromTo);
        $closed_data = $this->get_closed_data($broker_id, $closed_fromTo);

        $bank_data = Bank::latest()->get();
        $session = null;
        if ($client->source == 'BNC') {
            $session = 1;
        }
        $finance = $this->orderService->getFinancialData($broker_id); //$this->get_financial_data($client->broker_id,$session);

        if ($tab == 'opened') {
            $opened_data = $opened_data->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $opened_data = $opened_data->latest()->paginate($limit, ['*'], 'page', 1);
        }

        if ($tab == 'closed') {
            $closed_data = $closed_data->latest()->paginate($limit, ['*'], 'page', $page);
            //dd($closed_data);die('b');
        } else {
            $closed_data = $closed_data->latest()->paginate($limit, ['*'], 'page', 1);
        }

        if ($tab == 'trx') {
            $money_trx_data = $money_trx_data->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $money_trx_data = $money_trx_data->latest()->paginate($limit, ['*'], 'page', 1);
        }

        if ($tab == 'actions') {
            $actions = $actions->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $actions = $actions->latest()->paginate($limit, ['*'], 'page', 1);
        }

        if ($tab == 'history') {
            $money_history = $money_history->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $money_history = $money_history->latest()->paginate($limit, ['*'], 'page', 1);
        }

        if ($tab == 'kyc') {
            $kycs = $kycs->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $kycs = $kycs->latest()->paginate($limit, ['*'], 'page', 1);
        }

        return view('client.main_tp', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'moneytrx_request_data',
                        'moneyTrx_fromTo',
                        'money_trx_data',
                        'opened_fromTo',
                        'closed_fromTo',
                        'money_history',
                        'scripts_data',
                        'asset_groups',
                        'transactions',
                        'closed_data',
                        'opened_data',
                        'email_logs',
                        'bank_data',
                        'comments',
                        'statuses',
                        'request',
                        'finance',
                        'actions',
                        'filters',
                        'changes',
                        'status',
                        'client',
                        'users',
                        'next',
                        'from',
                        'chat',
                        'kycs',
                        'pre',
                        'tab',
                        'to',
                ));
    }

    public function update(Request $request, $id) {
        //$options = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();
        $client = Client::findOrfail($id);

        $inputs = array_filter(
                $request->only([
                    'asset_group_id',
                    'account_type',
                    'sales_status',
                    'first_name',
                    'last_name',
                    'username',
                    'leverage',
                    'country',
                    'phone1',
                    'phone2',
                    'is_ftd',
                    'email',
                    'usdt',
                    'age',
                ]),
                function ($value, $key) use ($request) {
                    return $request->has($key);
                },
                ARRAY_FILTER_USE_BOTH
        );

        if (isset($inputs['account_type']) && $inputs['account_type'] != $client->account_type) {
            $subscription = Auth::user()->pipeline->subscription()->where('active', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
            
            if($inputs['account_type'] == 'Real'){
                $currentRealAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]],['field'=>'account_type','conditions'=>['='=>'Real']]]));
                if ($currentRealAccountsCount >= $subscription->real_accounts) {
                    session()->flash('fail', 'You have reached your maximum count of real accounts');
                    return response()->json([
                        'success' => false  ,
                    ]);
                }
            } elseif($inputs['account_type'] == 'Demo') {
                $currentDemoAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]],['field'=>'account_type','conditions'=>['='=>'Demo']]]));
                if ($currentDemoAccountsCount >= $subscription->demo_accounts) {
                    session()->flash('fail', 'You have reached your maximum count of demo accounts');
                    return response()->json([
                        'success' => false  ,
                    ]);
                }
            }
        }

        if ($request->user_id && !empty($request->user_id)) {
            $inputs = array_merge($inputs, [
                'user_id' => $request->user_id,
            ]);
            if ($request->user_id != $client->user_id) {
                $inputs = array_merge($inputs, [
                    'is_notified' => 1,
                    'notified_at' => now(),
                ]);
            }
        }

        $password_text = $client->password_text;

        if ($request->password && $request->password != $client->password_text) {
            $inputs = array_merge($inputs, [
                'password' => Hash::make($request->password),
                'password_text' => $request->password,
            ]);
        }

        $status = $request->input('sales_status');
        $is_ftd = $request->input('is_ftd');

        if ($status != $client->sales_status && $status != null) {
            Report::create([
                'client_id' => $id,
                'modified_by' => Auth::id(),
                'new_status' => $status,
                'type' => 'Client'
            ]);

            Action::create([
                'client_id' => $id,
                'user_id' => Auth::id(),
                'text' => 'Updated From <span class="text-danger">' . $client->sales_status . '</span> To <span class="text-primary">' . $status . '</span>'
            ]);
        }

        $client->update($inputs);

        session()->flash('success', 'Client have been updated successfully.');

        return response()->json([
                    'success' => true,
        ]);
    }

    public function get_financial_data($broker_id) {

        $finance = [];
        $finance['last_deposit_amount'] = 0.00;
        $finance['totalWithdrawal'] = 0.00;
        $finance['totalDeposit'] = 0.00;
        $finance['ftd_amount'] = 0.00;
        $finance['usedMargin'] = 0.00;
        $finance['currentPL'] = 0.00;
        $finance['balance'] = 0.00;
        $finance['credit'] = 0.00;
        $finance['equity'] = 0.00;
        $finance['bonus'] = 0.00;
        $finance['freeMargin'] = 0.00;
        $finance['closedOrdersPL'] = 0.00;

        $client = Client::where('broker_id', $broker_id)->where('deleted', 0)->first();

        if ($client) {
            $deposits = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'deposit')
                    ->sum('money_trx_details.amount');

            $finance['totalDeposit'] = $deposits;

//            $lastDeposit = MoneyTrx::where('broker_id', $broker_id)
//                ->where('status', 'accepted')
//                ->where('type', 'deposit')
//                ->orderBy('created_at', 'desc')
//                ->first();

            $lastDeposit = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'deposit')
                    ->orderBy('money_trxes.created_at', 'desc')
                    ->first();
            // var_dump($lastDeposit->amount);die;



            $finance['last_deposit_amount'] = $lastDeposit ? $lastDeposit->amount : 0.00;
            $finance['ftd_amount'] = $finance['last_deposit_amount'];

//            $withdrawals = MoneyTrx::where('broker_id', $broker_id)
//                ->where('status', 'accepted')
//                ->where('type', 'withdraw')
//                ->sum('amount');

            $withdrawals = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'withdraw')
                    ->sum('money_trx_details.amount');

            $finance['totalWithdrawal'] = $withdrawals;

            $openedOrders = Order::where('broker_id', $broker_id)->whereNull('closed_at')->get();
            $finance['usedMargin'] = $openedOrders->sum('required_margin');
            $finance['currentPL'] = $openedOrders->sum('pnl');

            $closedOrdersPL = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->sum('pnl');
            $finance['closedOrdersPL'] = $closedOrdersPL;

            //print_r($closedOrdersPL);die;
            // $creditIn = MoneyTrx::where('broker_id', $broker_id)->where('status', 'accepted')->where('type', 'credit in')->sum('amount');

            $creditIn = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'credit in')
                    ->sum('money_trx_details.amount');

            // $creditOut = MoneyTrx::where('broker_id', $broker_id)->where('status', 'accepted')->where('type', 'credit out')->sum('amount');

            $creditOut = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'credit out')
                    ->sum('money_trx_details.amount');
            // var_dump($creditOut);die;

            $finance['credit'] = $creditIn - $creditOut;

            // if($_GET['test'] == 'yes'){
            //     echo "totalDeposit: ".$finance['totalDeposit']."  -  totalWithdrawal: ".$finance['totalWithdrawal']." - closedOrdersPL: ".$closedOrdersPL." - credit: ".$finance['credit']." - bonus:".$finance['bonus'];die;
            // }
            $finance['balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']) + $closedOrdersPL + $finance['credit'];

            //echo $finance['totalDeposit'] ."+". $finance['totalWithdrawal'] ."+".  $closedOrdersPL ."+".$finance['credit'];die;

            $finance['withdraw_balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal'])+$closedOrdersPL;

            //$bonusIn = MoneyTrx::where('broker_id', $broker_id)->where('status', 'accepted')->where('type', 'bonus in')->sum('amount');

            $bonusIn = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'bonus in')
                    ->sum('money_trx_details.amount');

            //$bonusOut = MoneyTrx::where('broker_id', $broker_id)->where('status', 'accepted')->where('type', 'bonus out')->sum('amount');

            $bonusOut = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                    ->where('money_trxes.broker_id', $broker_id)
                    ->where('money_trxes.status', 'accepted')
                    ->where('money_trx_details.type', 'bonus out')
                    ->sum('money_trx_details.amount');

            $finance['bonus'] = $bonusIn - $bonusOut;

            $finance['equity'] = $finance['balance'] + $finance['currentPL'] + $finance['bonus'];
            //  echo $finance['currentPL'];die;


            $finance['freeMargin'] = ($finance['balance'] - $finance['usedMargin']) + $finance['bonus'];
        }

        return $finance;
    }

    public function update_yes_no(Request $request, $id) {
        $client = Client::findOrfail($id);

        if ($request->def) {
            $options['enableWithdrawalRequest'] = 1;
            $options['enableDepositRequest'] = 1;
            $options['isEnabled'] = 1;
            $inputs['options'] = $options;
        } else {
            $inputs = $request->only([
                'options',
            ]);
        }

        $client->update($inputs);

        return redirect()->back()->with('success', 'Client options has been updated successfully.');
    }

    public function update_kyc(Request $request, $id) {
        $kyc = ClientDocument::findOrfail($id);

        $inputs = $request->only([
            'status',
        ]);

        $kyc->update($inputs);

        if ($request->status == 'accepted') {
            $client = Client::find($kyc->client_id);
            $options = $client->options ?? [];
            $options['isVerified'] = 1;
            $client->update([
                'options' => $options
            ]);
        }

        return redirect()->back()->with('success', 'Kyc status has been updated successfully.');
    }

    public function get_closed_data($broker_id, $closed_fromTo) {
        $dates = preg_split('/\s*-\s*/', trim($closed_fromTo));

        if (isset($dates[0]) && !empty($dates[0])) {
            $from = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
        }

        if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
            $to = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $to = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
        }//echo $from.' - '.$to;die;
        $closed_data = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->where('created_at', '>=', $from)->where('created_at', '<=', $to);

        return $closed_data;
    }

    public function get_opened_data($broker_id, $opened_fromTo) {
        $dates = preg_split('/\s*-\s*/', trim($opened_fromTo));

        if (isset($dates[0]) && !empty($dates[0])) {
            $from = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
        }

        if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
            $to = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $to = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
        }
        $opened_data = Order::where('broker_id', $broker_id)->whereNull('closed_at');
        return $opened_data;
    }

    public function get_trx_data($broker_id, $moneyTrx_fromTo) {
        $dates = preg_split('/\s*-\s*/', trim($moneyTrx_fromTo));

        if (isset($dates[0]) && !empty($dates[0])) {
            $from = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
        }

        if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
            $to = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $to = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
        }
        $money_trx_data = MoneyTrx::where('broker_id', $broker_id)->where('status', 'accepted')->where('created_at', '>=', $from)->where('created_at', '<=', $to);

        return $money_trx_data;
    }

    public function get_scripts_data($id) {
        $client = Client::with('assetGroup')->find($id);
        if (!$client || !$client->assetGroup) {
            return [];
        }

        $asset_ids = $client->assetGroup->assetAssignments->pluck('asset') ?? [];
        //$assets = Asset::whereIn('id', $asset_ids)->whereIn('type', ['Crypto','Forex','Stocks','Indx'])->where('bid_price','!=',0)->get();
        $assets = $this->assetService->getByFilters([
            ['field' => 'id', 'conditions' => ['in' => $asset_ids]],
            ['field' => 'type', 'conditions' => ['in' => ['Crypto', 'Forex', 'Stocks', 'Indx']]],
            ['field' => 'bid_price', 'conditions' => ['!=' => 0]],
        ]);
        $asset_group_id = $client->asset_group_id;
        $assets->load(['groupAssignments' => function ($query) use ($asset_group_id) {
                $query->where('asset_group', $asset_group_id);
            }]);
        /* foreach ($assets as $asset) {
          $defaults = [
          'sell_commission' => 0,
          'buy_commission'  => 0,
          'is_percentage'   => 0,
          'bid_spread'      => 0,
          'ask_spread'      => 0,
          'leverage'        => 0,
          'size'            => 0,
          ];

          $asset->fill(array_merge($defaults, [
          'sell_commission' => $asset->sell_commission[$asset_group_id] ?? 0,
          'buy_commission'  => $asset->buy_commission[$asset_group_id] ?? 0,
          'is_percentage'   => $asset->is_percentage[$asset_group_id] ?? 0,
          'bid_spread'      => $asset->bid_spread[$asset_group_id] ?? 0,
          'ask_spread'      => $asset->ask_spread[$asset_group_id] ?? 0,
          'leverage'        => $asset->leverage[$asset_group_id] ?? 0,
          'size'            => $asset->size[$asset_group_id] ?? 0,
          ]));
          } */

        return $assets;
    }

    public function create_money_transaction(Request $request, $id) {
        $client = Client::findOrFail($id);
        $inputs = $request->only([
            'comment',
            'amount',
            'type',
        ]);
        $inputs = array_merge($inputs, [
            'broker_id' => $client->broker_id,
            'is_admin' => 1,
            'status' => 'accepted',
        ]);
        $trx = MoneyTrx::create($inputs);

        $trxDetailsArray = array('money_trx' => $trx->id, 'type' => $inputs['type'], 'amount' => $inputs['amount']);
        $trxDetails = MoneyTrxDetail::create($trxDetailsArray);

        $history_inputs = [
            'operation_id' => $trx->id,
            'client_id' => $id,
            'user_id' => Auth::id(),
            'type' => 'New',
            'part' => 'Money Transaction',
            'text' => 'Created <b>' . $request->type . '</b> Transaction with <b>' . $request->amount . '</b> amount.',
        ];
        MoneyHistory::create($history_inputs);
        $mopType = [
            'withdraw' => -1,
            'deposit' => 1,
            'credit in' => 3,
            'credit out' => -3,
            'bonus out' => 2,
            'bonus in' => 2,
        ];
        if ($client->source != 'BNC') {
            $inputs = [
                'AccountID' => $client->broker_id,
                'TransType' => $mopType[$request->type],
                'Amount' => $request->type == 'bonus out' ? $request->amount * -1 : $request->amount,
                'Description' => ($request->type == 'bonus in' || $request->type == 'bonus out') ? $request->type : '' . $request->comment,
            ];
        }
        session()->flash('success', 'Money Transaction has been created successfully.');

        return response()->json([
                    'success' => true,
        ]);
    }

    public function request(Request $request, $id) {
        $inputs = $request->only([
            'bank_details',
            'bank_id',
            'comment',
            'amount',
            'type',
            'usdt',
        ]);
        $inputs['broker_id'] = $id;
        $inputs['is_admin'] = 1;

        if ($request->type == 'withdraw') {
            if ($request->paymentType == 'usdt') {
                $inputs['bank_details'] = null;
            } else {
                $inputs['usdt'] = null;
            }
        } else {
            $inputs['bank_details'] = null;
            $inputs['usdt'] = null;
        }

        if ($request->receipt) {
            $receipt = $request->file('receipt')->store('public/receipts');
            $inputs['receipt'] = str_replace('public/', 'storage/', $receipt);
        }

        $trx = MoneyTrx::create($inputs);

        $history_inputs = [
            'operation_id' => $trx->id,
            'client_id' => $trx->client->id,
            'user_id' => Auth::id(),
            'type' => 'New',
            'part' => 'Money Transaction Request',
            'text' => 'Created <b>' . $request->type . '</b> Transaction Request with <b>' . $request->amount . '</b> amount.',
        ];
        MoneyHistory::create($history_inputs);

        return redirect()->back()->with('success', 'Request has been created successfully.');
    }

    public function open_order(Request $request, $id) {
        $inputs = $request->only([
            'required_margin',
            'open_price',
            'currency',
            'comment',
            'amount',
            'type',
        ]);
        $asset = Asset::find($inputs['currency']);
        $inputs['broker_id'] = $id;
        $inputs['ref_currency'] = $asset->currency;

        $order = Order::create($inputs);
        if (!isset($order->client->options['ignoreLiquidation']) && $order->client->source == 'BNC') {
            $loop = true;

            while ($loop) {
                $order = Order::find($order->id);
                if ($order->pnl != null) {
                    $finance = $this->orderService->getFinancialData($order->broker_id); //$this->get_financial_data($order->broker_id,1);
                    if ($finance['equity'] < 0) {
                        $order->delete();
                        session()->flash('fail', 'Liquidation Failed, Equity is less than 0.');
                        return response()->json([
                                    'success' => true
                        ]);
                    }
                    $loop = false;
                }
            }
        }

        $history_inputs = [
            'operation_id' => $order->id,
            'client_id' => $order->client->id,
            'user_id' => Auth::id(),
            'type' => 'New',
            'part' => 'Order',
            'text' => 'Opened New order <b>' . ($request->type == 1 ? 'Buy' : 'Sell') . ' (' . $asset->name . ')</b> with <b>' . $request->amount . '</b> amount.',
        ];
        MoneyHistory::create($history_inputs);
        session()->flash('success', 'Order has been created successfully.');

        return response()->json([
                    'success' => true
        ]);
    }

    public function delete_order(Request $request, $id = null) {
        $ids = $request->only([
            'ids',
        ]);

        if ($id) {
            $ids = ['ids' => [$id]];
        }

        foreach ($ids['ids'] as $id) {
            $order = Order::whereIn('id', $ids['ids'])->first();
            $history_inputs = [
                'operation_id' => $id,
                'client_id' => $order->client->id,
                'user_id' => Auth::id(),
                'type' => 'Delete',
                'part' => 'Order',
                'text' => 'Deleted <b>' . ($order->type == 1 ? 'Buy' : 'Sell') . ' (' . $order->asset->name . ')</b> Order with <b>' . $order->amount . '</b> amount.',
            ];
            MoneyHistory::create($history_inputs);
        }

        Order::whereIn('id', $ids['ids'])->delete();

        session()->flash('success', 'Order Deleted successfully.');

        return response()->json([
                    'success' => true
        ]);
    }

    public function delete_money_trx(Request $request, $id = null) {
        $ids = $request->only([
            'ids',
        ]);

        if ($id) {
            $ids = ['ids' => [$id]];
        }

        foreach ($ids['ids'] as $id) {
            $trx = MoneyTrx::whereIn('id', $ids['ids'])->first();
            $history_inputs = [
                'operation_id' => $id,
                'client_id' => $trx->client->id,
                'user_id' => Auth::id(),
                'type' => 'Delete',
                'part' => 'Money Transaction',
                'text' => 'Deleted <b>' . $trx->type . '</b> Transaction with <b>' . $trx->amount . '</b> amount.',
            ];
            MoneyHistory::create($history_inputs);
        }
        MoneyTrxDetail::whereIn('money_trx', $ids['ids'])->delete();
        MoneyTrx::whereIn('id', $ids['ids'])->delete();

        session()->flash('success', 'Money Transaction Deleted successfully.');

        return response()->json([
                    'success' => true
        ]);
    }

    public function handle_request(Request $request, $id = null) {


        //gohere request
        $moneyTrx = MoneyTrx::findOrFail($id ?? $request->id);

        //echo $moneyTrx->is_admin;die('asd');
        //print_r($moneyTrx->client->options['canWithdrawalCredit']);die;
        //$moneyTrx->client->options['canWithdrawalBonus']
        $inputs = $request->only([
            'status',
            'comment'
        ]);
        $old_status = $moneyTrx->status;

        $inputs = ['status' => $request->status, 'comment' => $request->comment, 'updated' => 1];

        $client = $moneyTrx->client;
        $finance = $this->orderService->getFinancialData($client->broker_id); //$this->get_financial_data($client->broker_id);

        $credit = $finance['credit'] ?? 0;
        $bonus = $finance['bonus'] ?? 0;
        if (!isset($moneyTrx->is_admin) || $moneyTrx->is_admin == 0) {
            if (!isset($moneyTrx->client->options['canWithdrawalCredit']) || $moneyTrx->client->options['canWithdrawalCredit'] == 0) {
                $credit = 0;
            }
            if (!isset($moneyTrx->client->options['canWithdrawalBonus']) || $moneyTrx->client->options['canWithdrawalBonus'] == 0) {
                $bonus = 0;
            }
        }



        $amount = $moneyTrx->amount;

        //die('aa');
        $moneyTrx->update($inputs);
        // print_r($params);die;  
        //     $user    = Auth::guard('client')->user();
        // $options = $user->options??[];
        //  if (!isset($options['canWithdrawalCredit'])) {
        //  }
        //  die;



        $history_inputs = [
            'operation_id' => $moneyTrx->id,
            'client_id' => $moneyTrx->client->id,
            'user_id' => Auth::id(),
            'type' => 'Update',
            'part' => 'Money Transaction Request',
            'text' => 'Updated Status From <b><span class="text-danger">' . $old_status . '</span></b> To <span class="text-success"><b>' . $request->status . '</b></span>.',
        ];
        MoneyHistory::create($history_inputs);
        if ($moneyTrx->type == 'deposit') {
            if ($request->status == 'accepted') {
                Notification::create([
                    'client_id' => $moneyTrx->client->id,
                    'text' => 'deposit_accepted_notification',
                ]);
                $params = array('money_trx' => $moneyTrx->id, 'type' => 'deposit', 'amount' => $amount);
                MoneyTrxDetail::create($params);
            }
            if ($request->status == 'rejected') {
                Notification::create([
                    'client_id' => $moneyTrx->client->id,
                    'text' => 'deposit_rejected_notification',
                ]);
            }
        }
        if ($moneyTrx->type == 'withdraw') {
            if ($request->status == 'accepted') {
                Notification::create([
                    'client_id' => $moneyTrx->client->id,
                    'text' => 'withdraw_accepted_notification',
                ]);

                // new added
                $currentBalanceWithoutCredit = $finance['totalDeposit'] - $finance['totalWithdrawal'] + $finance['closedOrdersPL'];
                //echo $currentBalanceWithoutCredit.'<br>';
                // echo $finance['totalDeposit'] ." - ". $finance['totalWithdrawal'];
                // echo "<br>$credit<br>$bonus";die;
                // echo $currentBalanceWithoutCredit+$credit+$bonus;die;
                //  //echo $finance['totalDeposit'] ." - ". $finance['totalWithdrawal'];die;
                //$amount = 1520;
                //echo "currentBalance:$currentBalanceWithoutCredit<br> credit:$credit<br> bonus:$bonus<br> amount$amount";die;
                $params = array();

                if (($currentBalanceWithoutCredit - $amount) >= 0) {//die('a');
                    $params[0] = array('money_trx' => $moneyTrx->id, 'type' => 'withdraw', 'amount' => $amount);
                    //$trxDetails = MoneyTrxDetail::create($inputs);
                } else if (($currentBalanceWithoutCredit + $credit) - $amount >= 0) {
                    $params[0] = array('money_trx' => $moneyTrx->id, 'type' => 'withdraw', 'amount' => $currentBalanceWithoutCredit);
                    $params[1] = array('money_trx' => $moneyTrx->id, 'type' => 'credit out', 'amount' => ($amount - $currentBalanceWithoutCredit));
                } else if (($currentBalanceWithoutCredit + $credit + $bonus) - $amount >= 0) {
                    $params[0] = array('money_trx' => $moneyTrx->id, 'type' => 'withdraw', 'amount' => $currentBalanceWithoutCredit);
                    $params[1] = array('money_trx' => $moneyTrx->id, 'type' => 'credit out', 'amount' => $credit);
                    $params[2] = array('money_trx' => $moneyTrx->id, 'type' => 'bonus out', 'amount' => ($amount - ($currentBalanceWithoutCredit + $credit)));
                } else if (($currentBalanceWithoutCredit + $credit + $bonus) - $amount < 0) {

                    if ($bonus > 0) {
                        $params[] = array('money_trx' => $moneyTrx->id, 'type' => 'bonus out', 'amount' => $bonus);
                    }
                    if ($credit > 0) {
                        $params[] = array('money_trx' => $moneyTrx->id, 'type' => 'credit out', 'amount' => $credit);
                    }
                    $newAmount = abs(($currentBalanceWithoutCredit + $credit + $bonus) - $amount);
                    $params[] = array('money_trx' => $moneyTrx->id, 'type' => 'withdraw', 'amount' => $newAmount);
                    //echo $currentBalanceWithoutCredit."+".$credit."+".$bonus.")-".$amount;
                }
//          print_r($params);
//       die('aas');
                if (!empty($params)) {
                    MoneyTrxDetail::insert($params);
                }
                // end of new added
            }
            if ($request->status == 'rejected') {
                Notification::create([
                    'client_id' => $moneyTrx->client->id,
                    'text' => 'withdraw_rejected_notification',
                ]);
            }
        }
//        if ($request->status == 'accepted') {
//            $mopType = [
//                'deposit'    => 1,
//                'withdraw'   => -1,
//                'credit in'  => 3,
//                'credit out' => -3,
//            ];
//            if ($moneyTrx->type == 'withdraw') {
//                $client = $moneyTrx->client;
//                $finance = $this->get_financial_data($client->broker_id);
//              
//                 //   print_r($finance);die;
//              
//                $credit = $finance['credit'] ?? 0;
//                $bonus = $finance['bonus'] ?? 0;
//    
//                // if ($credit > 0) {
//                //     MoneyTrx::create([
//                //         'broker_id' => $client->broker_id,
//                //         'client_id' => $client->id,
//                //         'type'      => 'credit out',
//                //         'amount'    => $credit,
//                //         'status'    => 'accepted',
//                //         'is_admin'  => 1,
//                //         'comment'   => 'Auto removal of credit on withdrawal acceptance',
//                //     ]);
//                // }
//                // if ($bonus > 0) {
//                //     MoneyTrx::create([
//                //         'broker_id' => $client->broker_id,
//                //         'client_id' => $client->id,
//                //         'type'      => 'bonus out',
//                //         'amount'    => $bonus,
//                //         'status'    => 'accepted',
//                //         'is_admin'  => 1,
//                //         'comment'   => 'Auto removal of bonus on withdrawal acceptance',
//                //     ]);
//                // }
//            }
//
//        }


        if (!$id) {
            return redirect()->back()->with('success', 'Request has been updated successfully.');
        }
    }

    public function multi_handle_request(Request $request) {
        $ids = $request->request_ids;
        foreach ($ids as $id) {
            $this->handle_request($request, $id);
        }
        return redirect()->back()->with('success', 'Requests has been updated successfully.');
    }

    public function close_opened_order(Request $request, $id = null) {
        $ids = $request->only([
            'ids',
        ]);

        if ($id) {
            $ids = ['ids' => [$id]];
        }

        $inputs = [
            'closed_at' => Carbon::now(),
        ];

        Order::whereIn('id', $ids['ids'])->update($inputs);

        foreach ($ids['ids'] as $id) {
            $order = Order::whereIn('id', $ids['ids'])->first();
            $history_inputs = [
                'operation_id' => $id,
                'client_id' => $order->client->id,
                'user_id' => Auth::id(),
                'type' => 'Close',
                'part' => 'Order',
                'text' => 'Closed <b>' . ($order->type == 1 ? 'Buy' : 'Sell') . ' (' . $order->asset->name . ')</b> Order with <b>' . $order->amount . '</b> amount.',
            ];
            MoneyHistory::create($history_inputs);
        }


        session()->flash('success', 'Order Closed successfully.');
        return response()->json([
                    'success' => true,
        ]);
    }

    public function update_close_order(Request $request, $id) {

        $order = Order::findOrFail($id);

        $groupId = $group_id = $order->client->asset_group_id;
        $order->asset->load(['groupAssignments' => function ($query) use ($groupId) {
                $query->where('asset_group', $groupId);
            }]);
        $inputs = $request->only([
            'close_price',
            'open_price',
            'created_at',
            'closed_at',
            'comment',
            'amount',
        ]);
        $size = $order->asset?->groupAssignments?->first()?->size;
        $leverage = $order->asset?->groupAssignments?->first()?->leverage;
        if ($size === null || $leverage === null) {
            return response()->json([
                        'success' => false,
                        'message' => 'Order currency had been removed from asset group assigned to user, please choose another currency for the order.'
            ]);
        }
        if (str_starts_with($order->asset->symbol, 'USD') || (!strpos($order->asset->symbol, 'USD') && $order->asset->currency !== "USD")) {
            $reqMargin = (($request->amount * $request->open_price * $size) / $leverage) * (1 / $request->open_price);
        } else {
            $reqMargin = (($request->amount * $request->open_price * $size) / $leverage) * (1 / $request->open_price);
        }
        if (($order->asset->groupAssignments->first()->is_percentage ?? 0) == 1) {
            $reqMargin = ($request->amount * $request->open_price * $size) / $leverage;
        }
        $inputs['required_margin'] = $reqMargin;

        $old_text = "<span class='text-danger'>Created at : " . $order->created_at . "<br>Amount : " . $order->amount . "<br>Type : " . ($order->type == 1 ? 'Buy' : 'Sell') . "<br> Open price : " . $order->open_price . "<br> Close price : " . $order->close_price . "</span>";
        $old_pnl = $order->pnl;
        $old_closed_at = $order->closed_at;
        $old_close_price = $order->close_price;
        $old_open_price = $order->open_price;
        $old_amount = $order->amount;
        $old_comment = $order->comment;
        $old_created_at = $order->created_at;
        $order->update($inputs);

        //$this->calculate_pnl($order->client->id, $order->id);
        $this->orderService->calculatePnl($order);
        if (!isset($order->client->options['ignoreLiquidation']) && $order->client->source == 'BNC') {
            $order = Order::find($order->id);
            if ($order->pnl != $old_pnl) {
                $finance = $this->orderService->getFinancialData($order->broker_id); //$this->get_financial_data($order->broker_id,1);
                if ($finance['equity'] < 0) {
                    $order->update([
                        'closed_at' => $old_closed_at,
                        'close_price' => $old_close_price,
                        'pnl' => $old_pnl,
                        'open_price' => $old_open_price,
                        'amount' => $old_amount,
                        'comment' => $old_comment,
                        'created_at' => $old_created_at,
                    ]);
                    session()->flash('fail', 'Liquidation Failed, Equity is less than 0.');
                    return response()->json([
                                'success' => true,
                                'message' => 'Equity less than 0'
                    ]);
                }
            }
        }

        $new_text = "<span class='text-success'>Created at : " . $order->created_at . "<br>Amount : " . $order->amount . "<br>Type : " . ($order->type == 1 ? 'Buy' : 'Sell') . "<br> open price : " . $order->open_price . "<br> Close price : " . $order->close_price . "</span>";
        $history_inputs = [
            'operation_id' => $id,
            'client_id' => $order->client->id,
            'user_id' => Auth::id(),
            'type' => 'Update',
            'part' => 'Order',
            'text' => 'Updated Closed Order From <b>' . $old_text . '<br></b> To <b>' . $new_text . '</b>',
        ];
        MoneyHistory::create($history_inputs);
        session()->flash('success', 'Order updated successfully.');
        return response()->json([
                    'success' => true,
                    'message' => 'success'
        ]);
    }

    public function reopen_close_order(Request $request, $id) {
        $order = Order::findOrFail($id);
        $inputs = [
            'closed_at' => null,
            'close_price' => null,
            'pnl' => null,
        ];
        $old_closed_at = $order->closed_at;
        $old_pnl = $order->pnl;
        $old_close_price = $order->close_price;
        $old_open_price = $order->open_price;
        $old_amount = $order->amount;
        $old_comment = $order->comment;
        $old_created_at = $order->created_at;
        $order->update($inputs);
        if (!isset($order->client->options['ignoreLiquidation']) && $order->client->source == 'BNC') {
            $loop = true;

            while ($loop) {
                $order = Order::find($order->id);
                if ($order->pnl != null) {
                    $finance = $this->orderService->getFinancialData($order->broker_id); //$this->get_financial_data($order->broker_id,1);
                    if ($finance['equity'] < 0) {
                        $order->update([
                            'closed_at' => $old_closed_at,
                            'close_price' => $old_close_price,
                            'pnl' => $old_pnl,
                            'open_price' => $old_open_price,
                            'amount' => $old_amount,
                            'comment' => $old_comment,
                            'created_at' => $old_created_at,
                        ]);
                        session()->flash('fail', 'Liquidation Failed, Equity is less than 0.');
                        return response()->json([
                                    'success' => true
                        ]);
                    }
                    $loop = false;
                }
            }
        }
        $history_inputs = [
            'operation_id' => $order->id,
            'client_id' => $order->client->id,
            'user_id' => Auth::id(),
            'type' => 'Update',
            'part' => 'Order',
            'text' => 'Reopened closed order <b>' . ($order->type == 1 ? 'Buy' : 'Sell') . ' (' . $order->asset->name . ')</b> with <b>' . $order->amount . '</b> amount.',
        ];
        MoneyHistory::create($history_inputs);
        session()->flash('success', 'Order reopened successfully.');

        return response()->json([
                    'success' => true,
        ]);
    }

    public function update_open_order(Request $request, $id) {
        $order = Order::findOrFail($id);
        $groupId = $group_id = $order->client->asset_group_id;
        $order->asset->load(['groupAssignments' => function ($query) use ($groupId) {
                $query->where('asset_group', $groupId);
            }]);

        $inputs = $request->only([
            'open_price',
            'created_at',
            'comment',
            'amount',
            'type',
        ]);
        $inputs['closed_at'] = Carbon::now();
        $size = $order->asset?->groupAssignments?->first()?->size;
        $leverage = $order->asset?->groupAssignments?->first()?->leverage;
        if ($size === null || $leverage === null) {
            return response()->json([
                        'success' => false,
                        'message' => 'Order currency had been removed from asset group assigned to user, please choose another currency for the order.'
            ]);
        }

        if (str_starts_with($order->asset->symbol, 'USD') || (!strpos($order->asset->symbol, 'USD') && $order->asset->currency !== "USD")) {

            $reqMargin = (($request->amount * $request->open_price * $size) / $leverage) * (1 / $request->open_price);
        } else {
            $reqMargin = (($request->amount * $request->open_price * $size) / $leverage) * (1 / $request->open_price);
        }
        if (($order->asset->groupAssignments->first()->is_percentage ?? 0) == 1) {
            $reqMargin = ($request->amount * $request->open_price * $size) / $leverage;
        }
        $inputs['required_margin'] = number_format($reqMargin, 2, '.', '');

        $old_text = "<span class='text-danger'>Created at : " . $order->created_at . "<br>Amount : " . $order->amount . "<br>Type : " . ($order->type == 1 ? 'Buy' : 'Sell') . "<br> open price : " . $order->open_price . "</span>";
        $old_pnl = $order->pnl;
        $old_open_price = $order->open_price;
        $old_created_at = $order->created_at;
        $old_comment = $order->comment;
        $old_amount = $order->amount;
        $old_type = $order->type;
        $order->update($inputs);
        $new_text = "<span class='text-success'>Created at : " . $order->created_at . "<br>Amount : " . $order->amount . "<br>Type : " . ($order->type == 1 ? 'Buy' : 'Sell') . "<br> open price : " . $order->open_price . "</span>";
        if (!isset($order->client->options['ignoreLiquidation']) && $order->client->source == 'BNC') {
            //$this->calculate_pnl($order->client->id, $order->id);
            $this->orderService->calculatePnl($order);
            $order = Order::find($order->id);
            if ($order->pnl != $old_pnl) {
                $finance = $this->orderService->getFinancialData($order->broker_id); //$this->get_financial_data($order->broker_id,1);
                if ($finance['equity'] < 0) {
                    $order->update([
                        'closed_at' => null,
                        'pnl' => $old_pnl,
                        'open_price' => $old_open_price,
                        'amount' => $old_amount,
                        'comment' => $old_comment,
                        'created_at' => $old_created_at,
                        'type' => $old_type,
                    ]);
                    session()->flash('fail', 'Liquidation Failed, Equity is less than 0.');
                    return response()->json([
                                'success' => true,
                                'message' => 'Equity less than 0'
                    ]);
                }
            }
        }
        $order->update([
            'closed_at' => null,
        ]);

        $history_inputs = [
            'operation_id' => $id,
            'client_id' => $order->client->id,
            'user_id' => Auth::id(),
            'type' => 'Update',
            'part' => 'Order',
            'text' => 'Updated open order From <b>' . $old_text . '<br></b> To <b>' . $new_text . '</b>',
        ];
        MoneyHistory::create($history_inputs);

        session()->flash('success', 'Order updated successfully.');

        return response()->json([
                    'success' => true,
                    'order' => [
                        'id' => $order->id,
                        'created_at' => $order->created_at->format('d/m/Y H:i'),
                    ],
                    'message' => 'success'
        ]);
    }

    public function update_money_trx(Request $request, $id) {
        $moneyTrx = MoneyTrx::findOrFail($id);
        $inputs = $request->only([
            'created_at',
            'comment',
            'amount',
            'type',
        ]);
        $old_text = "<span class='text-danger'>Created at : " . $moneyTrx->created_at . "<br>Amount : " . $moneyTrx->amount . "<br>Type : " . $moneyTrx->type . "</span>";
        $moneyTrx->update($inputs);
        $new_text = "<span class='text-success'>Created at : " . $moneyTrx->created_at . "<br>Amount : " . $moneyTrx->amount . "<br>Type : " . $moneyTrx->type . "</span>";
        ;

        $history_inputs = [
            'operation_id' => $id,
            'client_id' => $moneyTrx->client->id,
            'user_id' => Auth::id(),
            'type' => 'Update',
            'part' => 'Money Transaction',
            'text' => 'Updated From <b>' . $old_text . '<br></b> To <b>' . $new_text . '</b>',
        ];
        MoneyHistory::create($history_inputs);

        if ($moneyTrx->status == 'accepted') {
            MoneyTrxDetail::where('money_trx', $moneyTrx->id)->update([
                'amount' => $moneyTrx->amount,
                'type' => $moneyTrx->type
            ]);
        }

        session()->flash('success', 'Money Transaction updated successfully.');

        return response()->json([
                    'success' => true,
        ]);
    }

    public function update_request(Request $request, $id) {
        $moneyTrx = MoneyTrx::findOrFail($id);
        $inputs = $request->only([
            'bank_details',
            'created_at',
            'comment',
            'bank_id',
            'amount',
            'usdt',
        ]);
        $old_text = "<span class='text-danger'>Created at : " . $moneyTrx->created_at . "<br>Amount : " . $moneyTrx->amount . "<br>Type : " . $moneyTrx->type;
        if ($moneyTrx->type == 'withdraw') {
            if ($request->usdt) {
                $inputs['bank_details'] = null;
                $old_text .= "<br>USDT : " . $moneyTrx->usdt . "</span>";
            } else {
                $inputs['usdt'] = null;
                $old_text .= "<br>Bank Details : " . $moneyTrx->bank_details . "</span>";
            }
        } else {
            $inputs['bank_details'] = null;
            $inputs['usdt'] = null;
        }





        $moneyTrx->update($inputs);
        $new_text = "<span class='text-success'>Created at : " . $moneyTrx->created_at . "<br>Amount : " . $moneyTrx->amount . "<br>Type : " . $moneyTrx->type;
        if ($request->usdt) {
            $new_text .= "<br>USDT : " . $moneyTrx->usdt . "</span>";
        } else {
            $new_text .= "<br>Bank Details : " . $moneyTrx->bank_details . "</span>";
        }

        $history_inputs = [
            'operation_id' => $id,
            'client_id' => $moneyTrx->client->id,
            'user_id' => Auth::id(),
            'type' => 'Update',
            'part' => 'Money Transaction Request',
            'text' => 'Updated From <b>' . $old_text . '<br></b> To <b>' . $new_text . '</b>',
        ];
        MoneyHistory::create($history_inputs);

        session()->flash('success', 'Request updated successfully.');

        return response()->json([
                    'success' => true,
        ]);
    }

    public function get_pnl($client_id, $asset_id = null) {

        if ($asset_id) {
            $asset = Asset::find($asset_id);
        }
        $client = Client::find($client_id);
        if (!$client) {
            return [
                'online_text' => 'Client not found',
                'equity' => '0.00',
                'orders' => [],
                'online' => false,
                'pnl' => '0.000',
                'bid' => 0,
                'ask' => 0,
            ];
        }
        $broker_id = $client->broker_id;
        $orders = Order::where('broker_id', $broker_id)->whereNull('closed_at')->get();
        $totalOpenedPnl = $orders->sum('pnl');

        $orders = $orders->map(function ($order) {
            $orderArr = $order->toArray();
            $orderArr['created_at'] = $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : null;
            return $orderArr;
        });
//gohere
        //$MoneyTrxs = MoneyTrx::where('broker_id',$client->broker_id)->where('status','accepted')->select('amount','type')->latest()->get();
        $MoneyTrxs = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
                        ->where('money_trxes.broker_id', $client->broker_id)
                        ->where('money_trxes.status', 'accepted')
                        ->select('money_trx_details.amount', 'money_trx_details.type')->latest()->get();
        $totalDeposit = 0.00;
        $totalWithdrawal = 0.00;
        $credit = 0.00;
        $bonus = 0.00;
        foreach ($MoneyTrxs as $MoneyTrx) {
            if ($MoneyTrx->type == 'deposit') {
                $totalDeposit += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'withdraw') {
                $totalWithdrawal += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'credit in') {
                $credit += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'credit out') {
                $credit -= $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'bonus in') {
                $bonus += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'bonus out') {
                $bonus -= $MoneyTrx->amount;
            }
        }

        $balance = ($totalDeposit - $totalWithdrawal) + Order::where('broker_id', $client->broker_id)->whereNotNull('closed_at')->sum('pnl') + $credit;
        $equity = $balance + $totalOpenedPnl + $bonus;

        $data = [
            'online_text' => $client->is_online ? 'Online now' : 'Offline now',
            'equity' => number_format($equity, 2, '.', ','),
            'orders' => $orders,
            'online' => $client->is_online,
            'pnl' => number_format($totalOpenedPnl, 3, '.', ','),
        ];
        if ($asset_id) {
            $data['bid'] = $asset->bid_price;
            $data['ask'] = $asset->ask_price;
        } else {
            $data['bid'] = 0;
            $data['ask'] = 0;
        }
        return $data;
    }

    public function get_leverage_data($id, $broker_id) {
        $leverage = 100;
        $contractSize = 100000;

        return ['leverage' => $leverage, 'contractSize' => $contractSize];
    }

    public function retention(Request $request, $id = null) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $formattedNowFromDate = Carbon::now()->startOfMonth()->startOfDay()->format('d/m/Y');
        $formattedNowToDate = Carbon::now()->endOfDay()->format('d/m/Y');
        $moneyTrx_fromTo = $request->input('moneyTrx_fromTo', $formattedNowFromDate . ' - ' . $formattedNowToDate);
        $moneyTrx_fromTo = $moneyTrx_fromTo != 'null - null' && $moneyTrx_fromTo != null ? $moneyTrx_fromTo : $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $opened_fromTo = $request->input('opened_fromTo', $formattedNowFromDate . ' - ' . $formattedNowToDate);
        $opened_fromTo = $opened_fromTo != 'null - null' && $opened_fromTo != null ? $opened_fromTo : $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $closed_fromTo = $request->input('closed_fromTo', $formattedNowFromDate . ' - ' . $formattedNowToDate);
        $closed_fromTo = $closed_fromTo != 'null - null' && $closed_fromTo != null ? $closed_fromTo : $formattedNowFromDate . ' - ' . $formattedNowToDate;
        $transactions = null;
        $asset_groups = AssetGroup::where('pipeline_id', Auth::user()->pipeline_id)->get();
        $email_logs = EmailLog::where('client_id', $id)->where('type', 'real')->latest()->limit(6)->get();
        $comments = Client_comment::where('client_id', $id)->latest()->get();
        //$options              = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();
        $actions = Action::where('client_id', $id);
        $changes = null;
        $client = null;
        $clients = auth()->user()->retention_clients ?? [];
        $online = false;
        $teams = $this->clientService->getTeams(Auth::user()); //(new ClientsController)->getTeams($options);
        $limit = $request->input('limit', 6);
        $users = $this->clientService->getUsers($teams, Auth::user()); //(new ClientsController)->getUsers($teams);
        $parts = $this->clientService->getParts($teams, Auth::user()); //(new ClientsController)->getParts($teams);
        $page = $request->query('page', 1);
        $from = Carbon::now()->subYears(10)->format('Y-m-d H:i:s');
        $chat = Chat_ah::where('client_id', $id)->latest()->get();
        $tab = $request->input('tab', 'info');
        $to = Carbon::now()->format('Y-m-d H:i:s');
        $tab = $request->input('tab', 'opened');

        $statuses = Status::where(function ($query) use ($parts) {
                    $first = true;
                    foreach ($parts as $part) {
                        if ($first) {
                            $query->where('part_ids', 'LIKE', '%"' . $part->id . '"%');
                            $first = false;
                        } else {
                            $query->orWhere('part_ids', 'LIKE', '%"' . $part->id . '"%');
                        }
                    }
                })->latest()->get();

        if ($id) {
            $client = Client::findOrfail($id);
            $broker_id = $client->broker_id;
        }

        if ($filters = $request->get('filters', [])) {
            $actions->where(function ($query) use ($filters) {
                $query->where(function ($subquery) use ($filters) {
                    if ($textQuery = Arr::get($filters, 'search_actions')) {
                        $textQuery = strtolower($textQuery);
                        $textQuery = '%' . $textQuery . '%';
                        $subquery->where(DB::raw('LOWER(text)'), 'like', $textQuery)
                                ->orWhere(DB::raw('REPLACE(text,"\n", " ")'), 'like', $textQuery)
                                ->orWhere(DB::raw('REPLACE(text,"\r\n", " ")'), 'like', $textQuery)
                                ->orWhereHas('user', function ($subsubquery) use ($textQuery) {
                                    $subsubquery->where(DB::raw('LOWER(username)'), 'like', $textQuery)
                                            ->orWhere(DB::raw('LOWER(first_name)'), 'like', $textQuery)
                                            ->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                                })
                                ->orWhereHas('client', function ($subsubquery) use ($textQuery) {
                                    $subsubquery->where(DB::raw('LOWER(first_name)'), 'like', $textQuery)
                                            ->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                                })
                                ->orWhere(DB::raw('LOWER(client_id)'), 'like', $textQuery);
                    }
                });
                if ($fromDate = Arr::get($filters, 'fromTo_actions')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '>=', $formattedFromDate);
                    }
                    if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    }
                }
            });
        }

        $moneytrx_request_data = collect([]);
        $money_trx_data = collect([]);
        $scripts_data = collect([]);
        $opened_data = collect([]);
        $closed_data = collect([]);
        $bank_data = collect([]);
        $finance = collect([]);

        if ($client && $client->broker_id) {
            $session = 1;
            $online = $client->is_online;
            $moneytrx_request_data = MoneyTrx::where('broker_id', $client->broker_id)->where('status', 'pending')->get();
            $money_trx_data = $this->get_trx_data($client->broker_id, $moneyTrx_fromTo);
            $scripts_data = $this->get_scripts_data($client->id);
            $opened_data = $this->get_opened_data($client->broker_id, $opened_fromTo);
            $closed_data = $this->get_closed_data($client->broker_id, $closed_fromTo);
            $bank_data = Bank::latest()->get();
            $finance = $this->orderService->getFinancialData($client->broker_id); //$this->get_financial_data($client->broker_id,$session);

            if ($tab == 'opened') {
                $opened_data = $opened_data->latest()->paginate($limit, ['*'], 'page', $page);
            } else {
                $opened_data = $opened_data->latest()->paginate($limit, ['*'], 'page', 1);
            }

            if ($tab == 'closed') {
                $closed_data = $closed_data->latest()->paginate($limit, ['*'], 'page', $page);
            } else {
                $closed_data = $closed_data->latest()->paginate($limit, ['*'], 'page', 1);
            }

            if ($tab == 'trx') {
                $money_trx_data = $money_trx_data->latest()->paginate($limit, ['*'], 'page', $page);
            } else {
                $money_trx_data = $money_trx_data->latest()->paginate($limit, ['*'], 'page', 1);
            }

            if ($tab == 'actions') {
                $actions = $actions->latest()->paginate($limit, ['*'], 'page', $page);
            } else {
                $actions = $actions->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }
        if (!is_array($finance)) {
            $finance = [
                'last_deposit_amount' => 0.00,
                'totalWithdrawal' => 0.00,
                'totalDeposit' => 0.00,
                'ftd_amount' => 0.00,
                'usedMargin' => 0.00,
                'currentPL' => 0.00,
                'balance' => 0.00,
                'credit' => 0.00,
                'equity' => 0.00,
                'bonus' => 0.00,
                'freeMargin' => 0.00,
            ];
        }

        return view('client.retention', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'moneytrx_request_data',
                        'moneyTrx_fromTo',
                        'money_trx_data',
                        'opened_fromTo',
                        'closed_fromTo',
                        'scripts_data',
                        'asset_groups',
                        'transactions',
                        'closed_data',
                        'opened_data',
                        'email_logs',
                        'bank_data',
                        'comments',
                        'statuses',
                        'finance',
                        'request',
                        'actions',
                        'filters',
                        'clients',
                        'changes',
                        'online',
                        'client',
                        'users',
                        'from',
                        'chat',
                        'tab',
                        'to',
                ));
    }

    public function add_client_to_retention(Request $request) {
        $client = Client::find($request->id);
        $user = User::find(auth()->id());
        if (!$client) {
            return redirect()->back()->with('fail', 'Client not found.');
        }
        if (!$client->broker_id) {
            return redirect()->back()->with('fail', 'Client has no TP.');
        }
        $clients = $user->retention_clients ?? [];
        foreach ($clients as $existingClient) {
            if ($existingClient['id'] == $client->id) {
                return redirect()->back()->with('fail', 'Client already added to retention.');
            }
        }
        $clients[] = [
            'id' => $client->id,
            'last_name' => $client->last_name,
            'first_name' => $client->first_name,
        ];

        $user->update([
            'retention_clients' => $clients,
        ]);

        return redirect()->route('main_tp.retention')->with('success', 'Client added to retention successfully.');
    }

    public function remove_client_from_retention($id) {
        $user = User::find(auth()->id());
        $clients = $user->retention_clients ?? [];

        $clients = array_values(array_filter($clients, fn($client) => $client['id'] != $id));

        $user->update(['retention_clients' => $clients]);

        return redirect()->route('main_tp.retention')->with('success', 'Client removed from retention successfully.');
    }

    /* public function calculate_pnl($client_id,$order_id)
      {
      $client = Client::find($client_id);
      $order  = Order::find($order_id);
      //$asset  = Asset::find($order->currency);
      $asset  = $this->assetService->getById($order->currency)->first();
      $groupId = $client->asset_group_id;
      $asset->load(['groupAssignments' => function($query) use ($groupId) {
      $query->where('asset_group', $groupId);
      }]);
      $assetGroupAssignment = $asset->groupAssignments->first();

      $currentPrice = $order->close_price;
      $openPrice = $order->open_price;

      if (!str_starts_with($order->asset->symbol, 'USD') && $order->ref_currency == 'USD') {
      if ($order->type == 1) {
      $def_price = $currentPrice -  $openPrice;
      }else{
      $def_price =  $openPrice - $currentPrice;
      }
      //$pnl = $order->amount * $asset->size[$client->asset_group_id] * $def_price;
      $pnl = $order->amount * $assetGroupAssignment->size * $def_price;
      }
      else  {
      $pipSize = $order->amount;

      if ($order->type == 1) {
      $pipDiff = ($currentPrice -  $openPrice) / $pipSize;
      } else {
      $pipDiff = ( $openPrice - $currentPrice) / $pipSize;
      }

      $standardLot = $assetGroupAssignment->size;
      $pipValueJPY = $standardLot * $pipSize;

      $pipValueStandard = $pipValueJPY / $currentPrice;

      $pipValue = $pipValueStandard * $order->amount;

      $pnl = $pipDiff * $pipValue;

      }
      if ($order->pnl != $pnl || $order->close_price != $currentPrice) {
      $order->update([
      'pnl' => number_format($pnl, 3, '.', ''),
      'close_price' => $currentPrice,
      ]);
      }
      } */

    public function exportData(Request $request, $id) {
        $logo = $request->input('logo', 'bnc');

        $client = Client::find($id);
        if (!$client) {
            return response('Client not found', 404);
        }
        $brokerId = $client->broker_id;
        $type = $request->input('type');

        $totalDeposits = MoneyTrx::where('broker_id', $brokerId)
                ->where('type', 'deposit')
                ->where('status', '!=', 'rejected')
                ->sum('amount');

        $totalWithdrawals = MoneyTrx::where('broker_id', $brokerId)
                ->where('type', 'withdraw')
                ->where('status', '!=', 'rejected')
                ->sum('amount');

        $netDeposits = $totalDeposits - $totalWithdrawals;

        $totalClosedPnl = Order::where('broker_id', $brokerId)
                ->whereNotNull('closed_at')
                ->sum('pnl');

        $balanceNow = $netDeposits + $totalClosedPnl;

        $closedOrders = collect();
        $moneyTrxes = collect();

        if ($type === 'money_trxes') {
            $moneyTrxes = MoneyTrx::where('broker_id', $brokerId)->get();
        } elseif ($type === 'closed_orders') {
            $closedOrders = Order::where('broker_id', $brokerId)
                    ->whereNotNull('closed_at')
                    ->get();
        } else {
            $closedOrders = Order::where('broker_id', $brokerId)
                    ->whereNotNull('closed_at')
                    ->get();
            $moneyTrxes = MoneyTrx::where('broker_id', $brokerId)->get();
        }

        $closedOrders->transform(function ($order) {
            $order->closed_at = $order->closed_at ?? null;
            return $order;
        });

        $assets = \App\Models\Asset::all();

        $finance = $this->orderService->getFinancialData($brokerId); //$this->get_financial_data($brokerId);
        $freeMargin = $finance['freeMargin'] ?? 0.00;

        $pdf = Pdf::loadView('exports.client_export', [
            'totalWithdrawals' => $totalWithdrawals,
            'totalDeposits' => $totalDeposits,
            'closedOrders' => $closedOrders,
            'netDeposits' => $netDeposits,
            'moneyTrxes' => $moneyTrxes,
            'freeMargin' => $freeMargin,
            'balanceNow' => $balanceNow,
            'client' => $client,
            'assets' => $assets,
            'logo' => $logo,
        ]);

        $filename = 'client_' . $client->id . '_export.pdf';
        return $pdf->download($filename);
    }
}

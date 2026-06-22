<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MarketingEmailLog;
use App\Models\Client_comment;
use App\Models\EmailTemplate;
use App\Imports\ClientImport;
use Illuminate\Http\Request;
use App\Models\SenderEmail;
use Illuminate\Support\Arr;
use App\Models\AssetGroup;
use App\Models\EmailLog;
use App\Models\Action;
use App\Models\Asset;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\MoneyHistory;
use App\Models\MoneyTrx;
use App\Models\Order;
use App\Models\Report;
use App\Models\Status;
use App\Models\Part;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
//Services
//use App\Http\Services\Order\Interfaces\OrderServiceInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
//use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Http\Services\Order\Interfaces\OrderServiceInterface;
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;
use App\Facades\UserPermission;
use App\Http\Services\Asset\Interfaces\AssetGroupServiceInterface;
use App\Http\Services\SearchFilters\Interfaces\SearchFiltersServiceInterface;
use App\Models\ClientQuestion;

class ClientsController extends Controller {

    protected $clientService;
    //protected $userService;
    protected $orderService;
    protected $subscriptionService;
    protected $assetGroupService;
    protected SearchFiltersServiceInterface $searchFiltersService;

    public function __construct(
            ClientServiceInterface $clientService,
            // UserServiceInterface $userService,
            OrderServiceInterface $orderService,
            SubscriptionServiceInterface $subscriptionService,
            AssetGroupServiceInterface $assetGroupService,
            SearchFiltersServiceInterface $searchFiltersService,
    ) {
        $this->clientService = $clientService;
        // $this->userService = $userService;
        $this->orderService = $orderService;
        $this->subscriptionService = $subscriptionService;
        $this->assetGroupService = $assetGroupService;
        $this->searchFiltersService = $searchFiltersService;
    }

    /* public function __construct(
      ClientServiceInterface $clientService,
      ) {
      $this->clientService = $clientService;

      } */
    /* protected $orderService;

      public function __construct(
      OrderServiceInterface $orderService,
      ) {
      $this->orderService = $orderService;

      } */

    public function index(Request $request) {


        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $mycontact_filters = null;
        $contacts_filters = null;
        $broker_filters = null;
        $money_history = null;
        $hot_filters = null;
        $new_filters = null;
        $checktypes = [];
        $gb_filter = '';
        $mycontact = null;
        $contacts = null;
        //$options           = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();
        $actions = null;
        $broker = null;
        $new = null;
        $hot = null;
        $tab = $request->input('tab', 'contacts');

        if (Auth::user()->ledParts->count() > 0 || Auth::user()->ledTeams->count() > 0 || !Auth::user()->team) {
            $limit = $request->input('limit', 15);
        } else {
            $limit = $request->input('limit', 6);
        }

        $page = $request->query('page', 1);

        $teams = $this->getTeams();
        $users = $this->getUsers($teams);
        $parts = $this->getParts($teams);
        
       
        $teamId = Auth::user()->team?->id;

        $statuses = Status::whereHas('teams', function ($query) use ($teamId, $isSuperAdmin, $isPipelineAdmin) {
            if (!$isSuperAdmin && !$isPipelineAdmin) {
                $query->where('teams.id', $teamId);
            }
        })->latest()->get();

        // $statuses = Status::where(function ($query) use ($parts) {
        //             $first = true;
        //             foreach ($parts as $part) {
        //                 if ($first) {
        //                     $query->where('part_ids', 'LIKE', '%"' . $part->id . '"%');
        //                     $first = false;
        //                 } else {
        //                     $query->orWhere('part_ids', 'LIKE', '%"' . $part->id . '"%');
        //                 }
        //             }
        //         })->latest()->get();

        $sources = Client::where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));
                    if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        //if (isset($options['leads_data_show_unassigned_leads'])) {
                        $query->orWhere('user_id', null);
                    }
                })->where('deleted', 0)->where('source', '!=', null)->where('source', '!=', '')->orderBy('source', 'asc')->distinct()->pluck('source');

        $created_by_users = Client::where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));

                    //if (isset($options['leads_data_show_unassigned_leads'])) {
                    if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        $query->orWhere('user_id', null);
                    }
                })->where('created_by', '!=', null)->where('created_by', '!=', '')->orderBy('created_by', 'asc')->distinct()->pluck('created_by');

        //if (isset($options['leads_tabs_all_leads'])) {
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_all_leads')) {
            $contacts = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                $query->whereIn('user_id', $users->pluck('id'));

                //if (isset($options['leads_data_show_unassigned_leads'])) {
                if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_all_leads')) {
                    $query->orWhere('user_id', null);
                }
            });

            if (!isset($tab)) {
                $tab = $request->input('tab', 'contacts');
            }
            $checktypes = array_merge($checktypes, ['contacts']);
        }

        //if (isset($options['leads_tabs_my_leads'])) {
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_my_leads')) {
            $mycontact = Client::where('user_id', Auth::id());
            if (!isset($tab)) {
                $tab = $request->input('tab', 'myContact');
            }
            $checktypes = array_merge($checktypes, ['mycontact']);
        }

        //if (isset($options['leads_tabs_b2b'])) {
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_b2b')) {
            $broker = Client::where('broker_id', '!=', null)->where('account_type', 'Real')->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                $query->whereIn('user_id', $users->pluck('id'));

                //if (isset($options['leads_data_show_unassigned_leads'])) {
                if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                    $query->orWhere('user_id', null);
                }
            });

            if (!isset($tab)) {
                $tab = $request->input('tab', 'broker');
            }
            $checktypes = array_merge($checktypes, ['broker']);
        }

        //if (isset($options['leads_tabs_new'])) {
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_new')) {
            $new = Client::where('sales_status', 'New')->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                $query->whereIn('user_id', $users->pluck('id'));

                //if (isset($options['leads_data_show_unassigned_leads'])) {
                if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                    $query->orWhere('user_id', null);
                }
            });
            if (!isset($tab)) {
                $tab = $request->input('tab', 'new');
            }
            $checktypes = array_merge($checktypes, ['new']);
        }

        //if (isset($options['leads_tabs_hot'])) {
        /* foreach($users->pluck('id') as $ss){
          echo $ss.',';
          }
          dd($users->pluck('id')); */
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_hot')) {
            $hot = Client::where('sales_status', 'Hot Lead')->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                $query->whereIn('user_id', $users->pluck('id'));

                //if (isset($options['leads_data_show_unassigned_leads'])) {
                if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                    $query->orWhere('user_id', null);
                }
            });

            if (!isset($tab)) {
                $tab = $request->input('tab', 'hot');
            }
            $checktypes = array_merge($checktypes, ['hot']);
        }

        //if (isset($options['leads_tabs_actions'])) {
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_actions')) {
            $actions = Action::where('client_id', '!=', null)->where(function ($query) use ($users) {
                $query->whereIn('user_id', $users->pluck('id'));
            });

            if (!isset($tab)) {
                $tab = $request->input('tab', 'actions');
            }
        }

        //if (isset($options['leads_tabs_history'])) {
        if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'leads_tabs_history')) {
            $money_history = MoneyHistory::where('client_id', '!=', null)->where(function ($query) use ($users) {
                $query->whereIn('user_id', $users->pluck('id'));
            });

            if (!isset($tab)) {
                $tab = $request->input('tab', 'history');
            }
        }

        foreach ($checktypes as $check_type) {
            ${$check_type . '_filters'} = $request->get($check_type . '_filters', []);

            $filters = ${$check_type . '_filters'};

            if (!empty($filters)) {
                $this->searchFiltersService->getSetFilters($filters);
            }
        }

        foreach ($checktypes as $check_type) {
            ${$check_type . '_filters'} =  $this->searchFiltersService->getSetFilters();
            ${$check_type} = $this->searchFiltersService->applyFilters(${$check_type}, $filters);
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

        if ($gb_filter = $request->gb_filter) {
            $textQuery = strtolower($gb_filter);
            $textQuery = '%' . $textQuery . '%';
            foreach ($checktypes as $check_type) {
                ${$check_type}->where(function ($query) use ($textQuery) {
                    $query->where('id', 'like', $textQuery)
                            ->orWhere('first_name', 'like', $textQuery)
                            ->orWhere('last_name', 'like', $textQuery)
                            ->orWhere('country', 'like', $textQuery)
                            ->orWhere('email', 'like', $textQuery)
                            ->orWhere('phone1', 'like', $textQuery)
                            ->orWhere('phone2', 'like', $textQuery)
                            ->orWhere('account_type', 'like', $textQuery)
                            ->orWhere('created_by', 'like', $textQuery)
                            ->orWhere('source', 'like', $textQuery)
                            ->orWhere('smart_data', 'like', $textQuery)
                            ->orWhere('ark_data', 'like', $textQuery)
                            ->orWhere(DB::raw("
                        LOWER(CONCAT_WS(' ', COALESCE(first_name, ''), COALESCE(last_name, '')))
                    "), 'like', $textQuery);
                });
            }
        }

        foreach ($checktypes as $check_type) {
            $this->searchFiltersService->getSetSort($request->sort, $request->order == 'desc' ? 'desc' : 'asc');
            ${$check_type} = $this->searchFiltersService->applySort(${$check_type});
        }

        if ($tab == 'contacts' && $contacts) {
            $contacts = $contacts
    ->where(function ($query) use ($users, $isSuperAdmin, $isPipelineAdmin, $pipelineId) {

        $query->whereIn('user_id', $users->pluck('id'));

        if (
            $isSuperAdmin ||
            $isPipelineAdmin ||
            UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')
        ) {
            $query->orWhereNull('user_id');
        }

    })
    ->latest()
    ->paginate($limit, ['*'], 'page', $page);
       
        } else {
            if ($contacts) {
                $contacts = $contacts
    ->where('clients.deleted', 0)
    ->where(function ($query) use ($users, $isSuperAdmin, $isPipelineAdmin, $pipelineId) {

        $query->whereIn('user_id', $users->pluck('id'));

        if (
            $isSuperAdmin ||
            $isPipelineAdmin ||
            UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')
        ) {
            $query->orWhereNull('user_id');
        }

    })
    ->latest()
    ->paginate($limit, ['*'], 'page', 1);
             
        }
}
        if ($tab == 'myContact' && $mycontact) {
            $mycontact = $mycontact->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($mycontact) {
                $mycontact = $mycontact->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }

        if ($tab == 'new' && $new) {
            $new = $new->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($new) {
                $new = $new->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }

        if ($tab == 'hot' && $hot) {
            $hot = $hot->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($hot) {
                $hot = $hot->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }

        if ($tab == 'broker' && $broker) {
            $broker = $broker->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($broker) {
                $broker = $broker->where('clients.deleted', 0)->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }

        if ($tab == 'actions' && $actions) {
            $actions = $actions->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($actions) {
                $actions = $actions->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }

        if ($tab == 'history' && $money_history) {
            $money_history = $money_history->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($money_history) {
                $money_history = $money_history->latest()->paginate($limit, ['*'], 'page', 1);
            }
        }

        // Get countries that have clients for export dropdown
        $countries = $this->getCountriesWithClients();

        return view('client.index', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'mycontact_filters',
                        'created_by_users',
                        'contacts_filters',
                        'broker_filters',
                        'money_history',
                        'hot_filters',
                        'new_filters',
                        'gb_filter',
                        'mycontact',
                        'contacts',
                        'statuses',
                        'actions',
                        'sources',
                        'filters',
                        'broker',
                        'users',
                        'teams',
                        //'options',
                        'countries',
                        'tab',
                        'hot',
                        'new',
                ));
    }

    public function create() {

        //$options = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();
        $teams = $this->getTeams();
        $users = $this->getUsers($teams);
        $parts = $this->getParts($teams);

        $teamIds = $teams->pluck('id');
        $statuses = Status::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->latest()->get();
        
        // $statuses = Status::where(function ($query) use ($parts) {
        //             $first = true;
        //             foreach ($parts as $part) {
        //                 if ($first) {
        //                     $query->where('part_ids', 'LIKE', '%"' . $part->id . '"%');
        //                     $first = false;
        //                 } else {
        //                     $query->orWhere('part_ids', 'LIKE', '%"' . $part->id . '"%');
        //                 }
        //             }
        //         })->latest()->get();

        return view('client.create', compact(
                        'statuses',
                        'users',
                ));
    }

    public function moreInfo($id) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        $client = Client::findOrFail($id);
        $client->load('questionAnswers');
        $client->setRelation('questionAnswers', $client->questionAnswers->keyBy('client_question_id'));
        $allQuestions = ClientQuestion::all(); 

        return view('client.more_info', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'client',
                        'allQuestions'
                ));
    }

    public function store(CreateClientRequest $request) {
        $status = $request->input('sales_status');

        $inputs = $request->only([
            'sales_status',
            'first_name',
            'last_name',
            'country',
            'user_id',
            'phone1',
            'phone2',
            'source',
            'email',
            'created_by' => AUTH::user()->username,
        ]);

        
        $id = $this->generateUniqueCode();


        $defaultAssetGroup = $this->assetGroupService->getByFilters(
            [['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]],['field'=>'default','conditions'=>['='=>1]]]
        )->first();
        
        $inputs = array_merge($inputs, [
            'id' => $id,
            'created_by' => AUTH::user()->username,
            'asset_group_id' => $defaultAssetGroup->id,
        ]);

        if ($request->user_id && !empty($request->user_id)) {
            $inputs = array_merge($inputs, [
                'first_owner' => $request->user_id,
                'assigned_at' => Carbon::now(),
                'is_notified' => 1,
                'notified_at' => now(),
            ]);
        }

        if ($status == 'FTD') {
            $inputs = array_merge($inputs, [
                'ftd_date' => Carbon::now(),
                'is_ftd' => 1,
            ]);
        }

        Client::create($inputs);

        return redirect()->route('client.index');
    }

    public function show($id, Request $request) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        
        $client = Client::with('createdBy')->findOrFail($id);

        if ($client->deleted == 1) {
            return redirect()->back()->with('fail', 'Client not found !');
        }
        $marketingEmailLogs = MarketingEmailLog::where('client_id', $id);
        $emailTemplates = EmailTemplate::latest()->get();
        $senderEmails = SenderEmail::select('id', 'email')->latest()->get();
        $transactions = null;
        $asset_groups = AssetGroup::where('pipeline_id', Auth::user()->pipeline_id)->get();
        $email_logs = EmailLog::where('client_id', $id)->where('type', '!=', 'Demo')->where('type', '!=', 'real')->latest()->limit(6)->get();
        $limit = $request->input('limit', 6);
        $tab = $request->input('tab', 'info');
        $broker_id = $client->broker_id;
        $status = $request->status ?? $client->sales_status;
        $comments = Client_comment::where('client_id', $id)->latest()->get();
        $actions = Action::where('client_id', $id);
        $changes = null;
        $page = $request->query('page', 1);
        $kycs = ClientDocument::where('client_id', $id)->where('type', 'kyc');
        $otherDocuments = ClientDocument::where('client_id', $id)->where('type', 'other');
        $next = 1;
        $pre = 1;
        // $options            = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();/
        $teams = $this->getTeams();
        $users = $this->getUsers($teams);
        $parts = $this->getParts($teams);

        $contacts = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
            $query->whereIn('user_id', $users->pluck('id'));

            //if (isset($options['leads_data_show_unassigned_leads'])) {
            if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                $query->orWhere('user_id', null);
            }
        });

        $client = $contacts->findOrfail($id);
        
        $teamIds = $teams->pluck('id');
        $statuses = Status::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->latest()->get();

        // $statuses = Status::where(function ($query) use ($parts) {
        //             $first = true;
        //             foreach ($parts as $part) {
        //                 if ($first) {
        //                     $query->where('part_ids', 'LIKE', '%"' . $part->id . '"%');
        //                     $first = false;
        //                 } else {
        //                     $query->orWhere('part_ids', 'LIKE', '%"' . $part->id . '"%');
        //                 }
        //             }
        //         })->latest()->get();

        $nextClient = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));

                    if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        $query->orWhere('user_id', null);
                    }
                });

        $preClient = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));

                    if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        $query->orWhere('user_id', null);
                    }
                });

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

            
            if ($fromDate = Arr::get($filters, 'fromTo_other')) {
                $dates = preg_split('/\s*-\s*/', trim($fromDate));

                if (isset($dates[0]) && !empty($dates[0])) {
                    $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                    $otherDocuments->where('created_at', '>=', $formattedFromDate);
                }
                if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                    $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                    $otherDocuments->where('created_at', '<=', $formattedToDate);
                } else {
                    $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                    $otherDocuments->where('created_at', '<=', $formattedToDate);
                }
            }

            if ($status_other = Arr::get($filters, 'status_other')) {
                $otherDocuments->where('status', $status_other);
            }


            $marketingEmailLogs->where(function ($query) use ($filters) {
                $query->where(function ($subquery) use ($filters) {
                    if ($textQuery = Arr::get($filters, 'search_marketingEmailLogs')) {
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
                if ($fromDate = Arr::get($filters, 'fromTo_marketingEmailLogs')) {
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

        // $nextClient->where('sales_status', $status);
        // $preClient->where('sales_status', $status);
        
        $nextClient = $this->searchFiltersService->applyFilters($nextClient);
        $nextClient = $this->searchFiltersService->getNext($nextClient, $client);
        $preClient = $this->searchFiltersService->applyFilters($preClient);
        $preClient = $this->searchFiltersService->getPrev($preClient, $client);


        $marketingEmailLogs = $marketingEmailLogs->latest()->paginate($limit);
        $actions = $actions->latest()->paginate($limit);

        if ($nextClient == null) {
            $next = 0;
        }

        if ($preClient == null) {
            $pre = 0;
        }

        $api_data['last_deposit_amount'] = 0.00;
        $api_data['totalWithdrawal'] = 0.00;
        $api_data['totalDeposit'] = 0.00;
        $api_data['usedMargin'] = 0.00;
        $api_data['freeMargin'] = 0.00;
        $api_data['ftd_amount'] = 0.00;
        $api_data['currentPL'] = 0.00;
        $api_data['balance'] = 0.00;
        $api_data['credit'] = 0.00;
        $api_data['equity'] = 0.00;

        if ($tab == 'kyc') {
            $kycs = $kycs->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $kycs = $kycs->latest()->paginate($limit, ['*'], 'page', 1);
        }
        
        if ($tab == 'other') {
            $otherDocuments = $otherDocuments->latest()->paginate($limit, ['*'], 'page', $page);
        } else {
            $otherDocuments = $otherDocuments->latest()->paginate($limit, ['*'], 'page', 1);
        }

        if ($request->from_notifi == 1) {
            $client->update([
                'is_notified' => 0,
            ]);
        }

        return view('client.show', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'marketingEmailLogs',
                        'emailTemplates',
                        'senderEmails',
                        'transactions',
                        'asset_groups',
                        'email_logs',
                        'comments',
                        'api_data',
                        'statuses',
                        'actions',
                        'filters',
                        'changes',
                        'status',
                        'client',
                        'users',
                        'next',
                        'kycs',
                        'otherDocuments',
                        'pre',
                        'tab',
             //   'userAuth',
          //      'pipelineId',
             //   'isSuperAdmin',
                ));
        
    }

    public function slides($status, $move, $id) {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        //$user_controller = new UserController;
        $old_client = Client::findOrfail($id);
        //$options         = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams = $this->getTeams();
        $users = $this->getUsers($teams);

        $leads = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
            $query->whereIn('user_id', $users->pluck('id'));

            if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                $query->orWhere('user_id', null);
            }
        });

        if ($move == 'Next') {
            // $client = $leads->where('created_at', '<', $old_client->created_at)
            //         ->where('sales_status', $status)
            //         ->orderBy('created_at', 'desc')
            //         ->first();

            $client = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                $query->whereIn('user_id', $users->pluck('id'));

                if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                    $query->orWhere('user_id', null);
                }
            });

            $client = $this->searchFiltersService->applyFilters($client);
            $client = $this->searchFiltersService->getNext($client, $old_client);

        } else {
            // $client = $leads->where('created_at', '>', $old_client->created_at)
            //         ->where('sales_status', $status)
            //         ->orderBy('created_at', 'asc')
            //         ->first();

            $client = Client::where('clients.deleted', 0)->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                $query->whereIn('user_id', $users->pluck('id'));

                if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                    $query->orWhere('user_id', null);
                }
            });

            $client = $this->searchFiltersService->applyFilters($client);
            $client = $this->searchFiltersService->getPrev($client, $old_client);
        }

        if ($client) {
            return redirect()->route('client.show', ['client' => $client->id, 'status' => $status]);
        } else {
            return redirect()->route('client.show', ['client' => $client->id, 'status' => $status])->with('fail', 'No Leads found');
        }
    }

    public function webtrader_get_pnl($client_id, $asset_id = null, $from = null) {
        if ($asset_id) {
            $asset = Asset::find($asset_id);
        }
        $client = Client::find($client_id);
        $orders = Order::where('broker_id', $client->broker_id)->whereNull('closed_at')->get();
        $totalOpenedPnl = $orders->sum('pnl');
        $broker_id = $client->broker_id;

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
        $assets = Asset::select('bid_price', 'ask_price', 'id', 'last_bid', 'last_ask')->get();

        $data = [
            'online_text' => $client->online ? 'Online now' : 'Offline now',
            'equity' => number_format($equity, 2, '.', ','),
            'orders' => $orders,
            'assets' => $assets,
            'online' => $client->online,
            'pnl' => number_format($totalOpenedPnl, 3, '.', ','),
        ];
        if ($asset_id) {
            $data['bid'] = $asset->bid_price;
            $data['ask'] = $asset->ask_price;
        } else {
            $data['bid'] = 0;
            $data['ask'] = 0;
        }
        if ($from == 'fromClient') {
            $client->update([
                'is_online' => 1,
                'loggedAt' => Carbon::now(),
            ]);
            $client->markOnline();
        }
        return response()->json($data)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, OPTIONS')->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    public function setOffline($client_id) {
        $client = Client::findOrFail($client_id);

        \Log::info('Setting client offline', [
            'client_id' => $client_id,
            'previous_last_seen_at' => $client->last_seen_at,
            'time_now' => now()->toDateTimeString(),
        ]);

        $client->last_seen_at = now();
        $client->is_online = 0;
        $client->save();

        $client->refresh();
        \Log::info('Client offline updated', [
            'client_id' => $client_id,
            'new_last_seen_at' => $client->last_seen_at,
        ]);
    }

    public function multiEdit(Request $request) {
        $request->validate([
            'account_type' => ['nullable', 'string'],
            'sales_status' => ['nullable', 'string'],
            'ftd_amount' => ['nullable', 'numeric'],
            'client_id' => ['required', 'string'],
            'user_id' => ['nullable'],
            'country' => ['nullable', 'string'],
            'is_ftd' => ['nullable', 'string'],
        ]);

        $inputs = array_filter(
                $request->only([
                    'account_type',
                    'sales_status',
                    'ftd_amount',
                    'country',
                ]),
                function ($value, $key) use ($request) {
                    return $request->has($key) && !is_null($value);
                },
                ARRAY_FILTER_USE_BOTH
        );

        $clientIdsString = $request->input('client_id', '');
        $clientIds = explode(',', $clientIdsString);

        $clients = Client::whereIn('id', $clientIds)->get();

        if ($request->user_id == 'no') {
            $inputs = array_merge($inputs, [
                'user_id' => null,
            ]);
        }if ($request->user_id && $request->user_id != 'no') {
            $inputs = array_merge($inputs, [
                'user_id' => $request->user_id,
            ]);
        }

        foreach ($clients as $client) {
            foreach ($inputs as $field => $value) {
                if ($client->$field != $value) {
                    if ($field == 'user_id') {
                        $inputs = array_merge($inputs, [
                            'is_notified' => 1,
                            'notified_at' => now(),
                        ]);
                        $old_user = $client->user?->username;
                        $new_user = User::find($value);
                        if (!$new_user) {
                        
                            continue;
                        }
                        Action::create([
                            'client_id' => $client->id,
                            'user_id' => Auth::id(),
                            'text' => 'Updated <strong>Assigned user</strong> From <span class="text-danger">' . $old_user . '</span> To <span class="text-primary">' . $new_user->username . '</span>'
                        ]);
                    } else {
                        Action::create([
                            'client_id' => $client->id,
                            'user_id' => Auth::id(),
                            'text' => 'Updated <strong>' . ucfirst(str_replace('_', ' ', $field)) . '</strong> From <span class="text-danger">' . $client->$field . '</span> To <span class="text-primary">' . $value . '</span>'
                        ]);
                    }
                }
            }
        }

        Client::whereIn('id', $clientIds)->update($inputs);

        if ($request->user_id && !empty($request->user_id)) {
            Client::whereIn('id', $clientIds)->whereNull('first_owner')->update([
                'first_owner' => $request->user_id,
                'assigned_at' => Carbon::now(),
            ]);
        }

        if ($request->is_ftd == 'Active') {
            foreach ($clients as $client) {
                if ($client->is_ftd != 1) {
                    Action::create([
                        'client_id' => $client->id,
                        'user_id' => Auth::id(),
                        'text' => 'Updated <strong>FTD</strong> From <span class="text-danger">' . $client->is_ftd . '</span> To <span class="text-primary">1</span>'
                    ]);
                }
            }
            Client::whereIn('id', $clientIds)->whereNull('ftd_date')->update([
                'ftd_date' => Carbon::now(),
                'is_ftd' => 1,
            ]);
        }

        if ($request->is_ftd == 'InActive') {
            foreach ($clients as $client) {
                if ($client->is_ftd != 0) {
                    Action::create([
                        'client_id' => $client->id,
                        'user_id' => Auth::id(),
                        'text' => 'Updated <strong>FTD</strong> From <span class="text-danger">' . $client->is_ftd . '</span> To <span class="text-primary">0</span>'
                    ]);
                }
            }
            Client::whereIn('id', $clientIds)->whereNull('ftd_date')->update([
                'ftd_date' => null,
                'is_ftd' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Leads has been updated successfully.');
    }

    public function update(Request $request, $id) {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);

        //$options = $this->userService->getUserOptions(Auth::user());//(new UserController)->get_user_options();
        $client = Client::findOrfail($id);

        $inputs = array_filter(
                $request->only([
                    'asset_group_id',
                    'account_type',
                    'sales_status',
                    'first_name',
                    'last_name',
                    'country',
                    'phone1',
                    'phone2',
                    'is_ftd',
                    'email',
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
                    return redirect()->back()->withErrors('You have reached your maximum count of real accounts');
                }
            }else if($inputs['account_type'] == 'Demo')
            {
                $currentDemoAccountsCount = count($this->clientService->getByFilters([['field'=>'pipeline_id','conditions'=>['='=>Auth::user()->pipeline_id]],['field'=>'account_type','conditions'=>['='=>'Demo']]]));

    if ($currentDemoAccountsCount >= $subscription->demo_accounts) {
        return redirect()->back()->withErrors('You have reached your maximum count of real accounts');
    }
            }

            if(!isset($inputs['asset_group_id']) && !$client->asset_group_id){
                $asset_group = AssetGroup::where('pipeline_id', Auth::user()->pipeline_id)
                ->where('default', 1)
                ->first();
                $inputs['asset_group_id'] = $asset_group?->id;
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
        }else{
            if(!isset($client->user_id))
            $inputs = array_merge($inputs, [
                'user_id' => null,
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

        $fieldsToCheck = [
            'account_type',
            'first_name',
            'last_name',
            'country',
            'user_id',
            'phone1',
            'phone2',
            'is_ftd',
            'email',
        ];
       
        foreach ($fieldsToCheck as $field) {
            
            if (isset($inputs[$field]) && $client->$field != $inputs[$field]) {
                if ($field == 'user_id') {
                    $old_user = $client->user?->username;
                    $new_user = User::find($inputs[$field]);
                    if (!$new_user) {
                        
                        continue;
                    }
                    $action = Action::create([
                        'client_id' => $id,
                        'user_id' => Auth::id(),
                        'text' => 'Updated <strong>Assigned user</strong> From <span class="text-danger">' . $old_user . '</span> To <span class="text-primary">' . $new_user->username . '</span>'
                    ]);
                } else {
                    $action = Action::create([
                        'client_id' => $id,
                        'user_id' => Auth::id(),
                        'text' => 'Updated <strong>' . ucfirst(str_replace('_', ' ', $field)) . '</strong> From <span class="text-danger">' . $client->$field . '</span> To <span class="text-primary">' . $inputs[$field] . '</span>'
                    ]);
                }

                if ($field == 'email') {
                    Log::channel('telegram')->info(Auth::user()->username . ' ' . $action->text . ' On ' . $action->client->first_name . ' ' . $action->client->last_name);
                }
            }
        }

        if ($is_ftd != null) {
            if (!$client->ftd_date) {
                $inputs = array_merge($inputs, [
                    'ftd_date' => Carbon::now(),
                ]);
            }
        } else {
            //if (isset($options['leads_ftd'])) {
            if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'field_ftd_status_edit')) {
                $inputs = array_merge($inputs, [
                    'ftd_date' => null,
                    'is_ftd' => 0,
                ]);
            }
        }

        if ($status == 'FTD' && !$client->ftd_date) {
            $inputs = array_merge($inputs, [
                'ftd_date' => Carbon::now(),
                'is_ftd' => 1,
            ]);
        }

        if ($request->user_id && !empty($request->user_id) && !$client->first_owner) {
            $inputs = array_merge($inputs, [
                'first_owner' => $request->user_id,
                'assigned_at' => Carbon::now()
            ]);
        }

        $client->update($inputs);

        return redirect()->back()->with('success', 'Client has been updated successfully.');
    }

    public function editStatus(Request $request, $id) {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        //$user_controller = new UserController;
        //$options         = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $client = Client::findOrfail($id);
        $request->validate([
            'sales_status' => ['required', 'string'],
        ]);
        $inputs = $request->only([
            'sales_status'
        ]);

        $status = $request->input('sales_status');

        if ($status != $client->sales_status) {
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

        if ($status == 'FTD' && !$client->ftd_date) {
            $inputs = array_merge($inputs, [
                'ftd_date' => Carbon::now(),
                'is_ftd' => 1,
            ]);
        }

        if ($status != 'FTD') {
            if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'field_ftd_status_edit')) {
                //if (isset($options['leads_ftd'])) {
                $inputs = array_merge($inputs, [
                    'ftd_date' => null,
                    'is_ftd' => 0,
                ]);
            }
        }

        $client->update($inputs);

        return redirect()->back()->with('success', 'Status has been updated successfully.');
    }

    public function destroy(Request $request) {
        $clientids = $request->input('clientid', []);
        $inputs = [
            'deleted_at' => Carbon::now(),
            'deleted' => 1,
        ];

        foreach ($clientids as $clientid) {
            $client = Client::find($clientid);
            $clientName = $client->first_name . ' ' . $client->last_name;
            if ($client) {
                $client->update($inputs);
            }
            Action::create([
                'client_id' => $clientid,
                'user_id' => Auth::id(),
                'text' => '<span class="text-danger">Deleted the lead ' . $clientName . ' #' . $clientid . '</span>'
            ]);
        }

        return redirect()->back()->with('success', 'Leads Deleted Successfully');
    }

    public function renew($id) {
        $client = Client::findOrfail($id);
        $inputs = [
            'assigned_at' => null,
            'created_at' => Carbon::now(),
            'renewed_at' => Carbon::now(),
            'is_renew' => 1,
        ];

        $client->update($inputs);

        $client->comments()->delete();

        Action::create([
            'client_id' => $id,
            'user_id' => Auth::id(),
            'text' => '<span class="text-danger">Renewed the lead</span>'
        ]);

        return redirect()->back()->with('success', 'Lead Renewed Successfully');
    }

    public function kyc(Request $request, $id) {
        $client = Client::findOrFail($id);

        $request->validate([
            'experience' => ['nullable', 'string'],
            'job' => ['nullable', 'string'],
            'age' => ['required', 'numeric', 'min:18'],
            'birth' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        $experience = $request->input('experience');
        $experiencecheck = $request->input('experiencecheck');
        $job = $request->input('job');
        $status = $request->input('status');
        $age = $request->input('age');
        $birth = $request->input('birth');

        if ($status != $client->sales_status) {
            $report = new Report();

            $report->client_id = $client->id;
            $report->modified_by = Auth::id();
            $report->new_status = $status;
            $report->type = 'Client';

            $report->save();
        }

        $client->updated_by = Auth::id();

        $client->job_title = strip_tags($job);
        $client->age = strip_tags($age);
        $client->sales_status = strip_tags($status);
        $client->birth = strip_tags($birth);

        if ($experiencecheck != null) {
            if ($experience != null) {
                $client->experience = strip_tags($experience);
            } else {
                $client->experience = 'Yes';
            }
        } else {
            $client->experience = 'No';
        }

        if ($client->save() && Auth::user()->role->name == "Employee") {
            $action = new Action();

            $action->user_id = Auth::id();
            $action->client_id = $id;
            $action->text = 'Updated KYC';

            $action->save();
        }

        return redirect()->route('client.show', $id);
    }

    public function generateUniqueCode() {
        do {
            $referal_code = random_int(100000, 999999);
        } while (Client::where("id", $referal_code)->first());

        return $referal_code;
    }

    public function excelCheck(Request $request) {
        $fields = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'campaign' => 'Campaign',
            'country' => 'Country',
            'phone1' => 'Phone1',
            'phone2' => 'Phone2',
            'sales_status' => 'Status',
            'source' => 'Source',
            'gender' => 'Gender',
            'email' => 'Email',
            'age' => 'Age',
            'ad' => 'Ad',
        ];

        $questions = ClientQuestion::all();
        foreach ($questions as $question) {
            $fields[$question->id] = $question->question_text;
        }

        $file = $request->file('excel_file');
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'csv' && $extension != 'xlsx' && $extension != 'xls') {
            return redirect()->back()->with('fail', 'The file must be a file of type: csv, xlsx, xls.');
        }

        $path1 = $request->file('excel_file')->store('temp');

        $path = storage_path('app') . '/' . $path1;

        $data = Excel::toArray([], $file);

        $sheet = $data[0] ?? [];
        $headers = $sheet[0] ?? [];

        $rows = array_filter(array_slice($sheet, 1), function ($row) {
            return !empty(array_filter($row, fn($cell) => trim($cell) !== ''));
        });

        if (!empty($rows)) {
            return view('client.upload', compact(
                            'headers',
                            'fields',
                            'rows',
                            'path'
                    ));
        }

        return back()->with('fail', 'The file does not contain any rows after the header.');
    }

    public function excelUpload(Request $request) {

        $path = $request->path;
        $headers = $request->input('header', []);
        $import = new ClientImport($headers);
        HeadingRowFormatter::default('none');
        Excel::import($import, $path);

        $repeated = $import->repeated;
        $success = $import->success;
        $emptyFirstName = $import->emptyFirstName;
        $emptyCountry = $import->emptyCountry;
        $unknownCountry = $import->unknownCountry;
        $emptyPhone1 = $import->emptyPhone1;
        $emptyEmail = $import->emptyEmail;

        if (count($success) > 0) {
            $client = Client::first();
            $client_id = $client->id;
    
            Action::create([
                'client_id' => $client_id,
                'user_id'   => Auth::id(),
                'text'      => '<span class="text-primary">Uploaded ' .count($success). ' Leads</span>'
            ]);
            // return redirect()->route('client.index')->with('success', "Leads imported $success successfully. Repeated: $repeated, Empty/Invalid: $empty");
        }
        // return redirect()->route('client.index')->with('fail', "Leads imported $success successfully. Repeated: $repeated, Empty/Invalid: $empty");
        if(file_exists($path)){
            unlink($path);
        }
        return view('client.results', compact(
            'repeated',
            'success',
            'emptyFirstName',
            'emptyCountry',
            'unknownCountry',
            'emptyPhone1',
            'emptyEmail'
        )); 
    }

    function getTeams() {
        // return $this->clientService->getTeams($options, Auth::user());
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        $teams = collect();

        if (Auth::user()->ledTeams->count() > 0) {
            foreach (Auth::user()->ledTeams as $ledTeam) {
                if (!$teams->contains($ledTeam)) {
                    $teams = $teams->merge([$ledTeam]);
                }
            }
        }

        if (Auth::user()->ledParts->count() > 0) {
            $ledPartTeams = Auth::user()->ledParts->load('teams')->pluck('teams')->flatten();
            foreach ($ledPartTeams as $ledPartTeam) {
                if (!$teams->contains($ledPartTeam)) {
                    $teams = $teams->merge([$ledPartTeam]);
                }
            }
        }
        /* if($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(),$pipelineId , 'field_team_show')){
          if (isset($options['leads_data_show_teams']) && !empty($options['leads_data_show_teams'])) {
          $specificTeams = Team::whereIn('id', $options['leads_data_show_teams'])->get();
          foreach ($specificTeams as $specificTeam) {
          if (!$teams->contains($specificTeam)) {
          $teams = $teams->merge([$specificTeam]);
          }
          }
          } */

        $pipelineSupportIds = json_decode(Auth::user()->pipeline?->support_ids, true) ?? [];

        if (in_array(Auth::id(), $pipelineSupportIds) || $isPipelineAdmin || $isSuperAdmin) {
            $teams = Team::latest()->get();
        }

        return $teams;
    }

    function getUsers($teams) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $users = collect();
     
                $users = User::WithPipeline()->where(function ($query) use ($teams) {
                    $query->whereIn('team_id', $teams->pluck('id'))
                            ->orWhere('id', Auth::id());
                })->latest()->get();
            

        $pipelineSupportIds = json_decode(Auth::user()->pipeline?->support_ids, true) ?? [];

        if (in_array(Auth::id(), $pipelineSupportIds) || $isPipelineAdmin) {
            $users = User::WithPipeline()->latest()->get();
        }

        return $users;
    }

    function getParts($teams) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        $parts = collect();
        $parts = Part::whereIn('id', $teams->pluck('part_id'));

        //dd(Auth::user());
        //print_r(Auth::user()->team);die('b');
        if (Auth::user()->team) {
            $parts = $parts->orWhereIn('id', [Auth::user()->team->part_id]);
        }

        $parts = $parts->latest()->get();

        $pipelineSupportIds = json_decode(Auth::user()->pipeline?->support_ids, true) ?? [];

        if (in_array(Auth::id(), $pipelineSupportIds) || $isPipelineAdmin || $isPipelineAdmin) {
            $parts = Part::latest()->get();
        }

        return $parts;
    }

    public function exportData(Request $request, $id) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $logo = $request->input('logo');

        $client = Client::findOrFail($id);
        $brokerId = $client->broker_id;

        $totalDeposits = DB::table('money_trxes')
                ->where('broker_id', $brokerId)
                ->where('type', 'deposit')
                ->where('status', '!=', 'rejected')
                ->sum('amount');

        $totalWithdrawals = DB::table('money_trxes')
                ->where('broker_id', $brokerId)
                ->where('type', 'withdraw')
                ->where('status', '!=', 'rejected')
                ->sum('amount');

        $netDeposits = $totalDeposits - $totalWithdrawals;

        $totalClosedPnl = DB::table('orders')
                ->where('broker_id', $brokerId)
                ->whereNotNull('closed_at')
                ->sum('pnl');

        $balanceNow = $netDeposits + $totalClosedPnl;

        $finance = $this->orderService->getFinancialData($brokerId); //(new MainTPController)->get_financial_data($brokerId);
        $freeMargin = $finance['freeMargin'] ?? 0.00;

        $moneyTrxes = collect();
        $closedOrders = collect();

        if ($type === 'money_trxes') {
            $moneyTrxes = DB::table('money_trxes')
                    ->where('broker_id', $brokerId)
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->select(
                            DB::raw('"Money Transaction" as record_type'),
                            'created_at as time',
                            'amount',
                            'type as trx_type'
                    )
                    ->get();
        } elseif ($type === 'closed_orders') {
            $closedOrders = DB::table('orders')
                    ->join('assets', 'orders.currency', '=', 'assets.id')
                    ->where('orders.broker_id', $brokerId)
                    ->whereNotNull('orders.closed_at')
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                    })
                    ->select(
                            DB::raw('"Closed Order" as record_type'),
                            'orders.created_at as time',
                            'assets.name as script',
                            DB::raw('CASE WHEN orders.type = 1 THEN "Buy" WHEN orders.type = 2 THEN "Sell" END as order_type'),
                            'orders.amount',
                            'orders.open_price',
                            'orders.closed_at as close_time',
                            'orders.close_price',
                            'orders.pnl'
                    )
                    ->get();
        } else {
            $moneyTrxes = DB::table('money_trxes')
                    ->where('broker_id', $brokerId)
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->select(
                            DB::raw('"Money Transaction" as record_type'),
                            'created_at as time',
                            'amount',
                            'status',
                            'type as trx_type'
                    )
                    ->get();

            $closedOrders = DB::table('orders')
                    ->join('assets', 'orders.currency', '=', 'assets.id')
                    ->where('orders.broker_id', $brokerId)
                    ->whereNotNull('orders.closed_at')
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                    })
                    ->select(
                            DB::raw('"Closed Order" as record_type'),
                            'orders.created_at as time',
                            'assets.name as script',
                            DB::raw('CASE WHEN orders.type = 1 THEN "Buy" WHEN orders.type = 2 THEN "Sell" END as order_type'),
                            'orders.amount',
                            'orders.open_price',
                            'orders.closed_at as closed_at',
                            'orders.close_price',
                            'orders.pnl'
                    )
                    ->get();
        }
        $assets = \App\Models\Asset::all();

        $html = view('exports.client_export', [
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
                ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'Amiri',
            'mode' => 'utf-8',
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'client_' . $client->id . '_transactions_' . now()->format('Ymd_His') . '.pdf';
        return response($mpdf->Output('', 'S'), 200)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function export(Request $request) {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        // Check if user is Admin
        // if (Auth::user()->role->name !== 'Admin') {
        if(!$isSuperAdmin && !$isPipelineAdmin){
            abort(403, 'Unauthorized access to export functionality.');
        }

        //$options = $this->userService->getUserOptions(Auth::user());
        $teams = $this->getTeams();
        $users = $this->getUsers($teams);

        // Build query for clients export
        $query = Client::where('clients.deleted', 0)
                ->leftJoin('users', 'clients.user_id', '=', 'users.id')
                ->select(
                        'clients.*',
                        'users.username as assigned_user'
                );

        // Apply user filter based on permissions
        $query->where(function ($q) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
            $q->whereIn('clients.user_id', $users->pluck('id'));
            //if (isset($options['leads_data_show_unassigned_leads'])) {
            if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                $q->orWhereNull('clients.user_id');
            }
        });

        // Apply filters from request
        if ($request->filled('assigned_user')) {
            $query->where('clients.user_id', $request->assigned_user);
        }

        if ($request->filled('country')) {
            $query->where('clients.country', $request->country);
        }

        if ($request->filled('account_type')) {
            $query->where('clients.account_type', $request->account_type);
        }

        // Handle status filtering - since clients table has sales_status as text field
        if ($request->filled('include_status') && is_array($request->include_status)) {
            // Get status names from the status IDs
            $statusNames = \App\Models\Status::whereIn('id', $request->include_status)->pluck('name');
            $query->whereIn('clients.sales_status', $statusNames);
        }

        if ($request->filled('exclude_status') && is_array($request->exclude_status)) {
            // Get status names from the status IDs
            $statusNames = \App\Models\Status::whereIn('id', $request->exclude_status)->pluck('name');
            $query->whereNotIn('clients.sales_status', $statusNames);
        }

        if ($request->filled('date_from')) {
            $query->where('clients.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('clients.created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Get the clients
        $clients = $query->get();

        // Create CSV content
        $filename = 'clients_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV Header
            fputcsv($file, [
                'ID',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Country',
                'Account Type',
                'Status',
                'Assigned User',
                'Registration Date',
                'FTD Date',
                'Created At'
            ]);

            // CSV Data
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->first_name,
                    $client->last_name,
                    $client->email,
                    $client->phone1,
                    $client->country,
                    $client->account_type,
                    $client->sales_status,
                    $client->assigned_user,
                    $client->reg_date,
                    $client->ftd_date,
                    $client->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getCountriesWithClients() {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        //$options = $this->userService->getUserOptions(Auth::user());
        $teams = $this->getTeams();
        $users = $this->getUsers($teams);

        // Get countries that have clients based on user permissions
        $countries = Client::where('clients.deleted', 0)
                ->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));
                    if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        //if (isset($options['leads_data_show_unassigned_leads'])) {
                        $query->orWhereNull('user_id');
                    }
                })
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->distinct()
                ->orderBy('country')
                ->pluck('country');

        // If this is a direct HTTP request, return JSON
        if (request()->expectsJson() || request()->is('*/client/countries')) {
            return response()->json([
                        'success' => true,
                        'countries' => $countries,
                        'total_countries' => $countries->count()
            ]);
        }

        // Otherwise return the collection for use in other methods
        return $countries;
    }

    public function getCountriesWithClientCounts() {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;
        $isPipelineAdmin = UserPermission::isPipelineAdmin(Auth::user(), $pipelineId);
        //$options = $this->userService->getUserOptions(Auth::user());
        $teams = $this->getTeams();
        $users = $this->getUsers($teams);

        // Get countries with client counts based on user permissions
        $countriesWithCounts = Client::where('clients.deleted', 0)
                ->where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));
                    //if (isset($options['leads_data_show_unassigned_leads'])) {
                    if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        $query->orWhereNull('user_id');
                    }
                })
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->selectRaw('country, COUNT(*) as client_count')
                ->groupBy('country')
                ->orderBy('client_count', 'desc')
                ->get();

        // If this is a direct HTTP request, return JSON
        if (request()->expectsJson() || request()->is('*/client/countries-stats')) {
            return response()->json([
                        'success' => true,
                        'countries' => $countriesWithCounts,
                        'total_countries' => $countriesWithCounts->count(),
                        'total_clients' => $countriesWithCounts->sum('client_count')
            ]);
        }

        // Otherwise return the collection
        return $countriesWithCounts;
    }

    public function clearFilters(){
        $this->searchFiltersService->clear();

        return redirect('/')->with('success', 'Filters cleard Successfully');
    }
}

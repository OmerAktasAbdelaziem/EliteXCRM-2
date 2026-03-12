<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Client_comment;
use App\Models\MoneyTrx;
use App\Models\Part;
use App\Models\OldRole;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
//use App\Http\Services\User\Interfaces\UserServiceInterface;
use App\Facades\UserPermission;

class OverviewController extends Controller {

    protected $clientService;

    //protected $userService;
    public function __construct(
            ClientServiceInterface $clientService,
            //UserServiceInterface $userService,
    ) {
        $this->clientService = $clientService;
        //$this->userService = $userService;
    }

    public function index(Request $request) {

        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $pipelineSupportIds = json_decode(Auth::user()->pipeline->support_ids, true) ?? [];
        $pipelineSupportIds = array_merge($pipelineSupportIds, [644033, 298274]);
        $date = $request->subMonth ?? Carbon::now()->subMonthNoOverflow()->format('m/Y');

        $currentMonthStartDate = Carbon::now()->startOfMonth();
        $last_month_days_leads = [];
        $currentMonthDaysCount = 0;
        $currentMonthEndDate = Carbon::now()->endOfDay();
        $lastMonthDaysCount = 0;
        $lastMonthStartDate = Carbon::createFromFormat('m/Y', $date)->startOfMonth();
        $lastMonthEndDate = Carbon::createFromFormat('m/Y', $date)->endOfMonth();
        $days_leads = [];
        $period = 'Monthly';

        //$clientsController = new ClientsController;
        // $mainTpController  = new MainTPController;
        //$user_controller   = new UserController;
        //$options = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams = $this->clientService->getTeams(Auth::user()); //$clientsController->getTeams($options);

        $users = $this->clientService->getUsers($teams, Auth::user())->whereNotIn('id', $pipelineSupportIds); //$clientsController->getUsers($teams)->whereNotIn('id',$pipelineSupportIds);
        $parts = $this->clientService->getParts($teams, Auth::user()); //$clientsController->getParts($teams);

        $leads = Client::where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                    $query->whereIn('user_id', $users->pluck('id'));
                    if ($isSuperAdmin || $isPipelineAdmin ||  UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                        //if (isset($options['leads_data_show_unassigned_leads'])) {
                        $query->orWhere('user_id', null);
                    }
                })->where('deleted', 0)->get();

        $comments = $this->getLastComments($leads->pluck('id'));
        $api_data = $this->get_total_financial_data($leads->pluck('id'));

        $leadsCount = $leads->count();
        $usersCount = $users->count();
        $partsCount = Part::count();
        $teamsCount = Team::count();
        $rolesCount = OldRole::count();

        function generateDaysInMonth($startDate, $endDate) {
            $days = [];
            for ($day = $startDate->day; $day <= $endDate->day; $day++) {
                $days[] = $startDate->copy()->day($day)->format('Y-m-d');
            }
            return $days;
        }

        $currentMonthDays = generateDaysInMonth($currentMonthStartDate, $currentMonthEndDate);
        $lastMonthDays = generateDaysInMonth($lastMonthStartDate, $lastMonthEndDate);

        $current_month_days_leads = Client::selectRaw('DATE(created_at) as day, COUNT(*) as count')
                ->where('created_at', '>=', $currentMonthStartDate)
                ->where('created_at', '<=', $currentMonthEndDate)
                ->groupBy('day')
                ->orderBy('day', 'asc')
                ->get()
                ->keyBy('day');

        $last_month_leads = Client::selectRaw('DATE(created_at) as day, COUNT(*) as count')
                ->where('created_at', '>=', $lastMonthStartDate)
                ->where('created_at', '<=', $lastMonthEndDate)
                ->groupBy('day')
                ->orderBy('day', 'asc')
                ->get()
                ->keyBy('day');

        foreach ($currentMonthDays as $day) {
            $count = $current_month_days_leads->get($day)->count ?? 0;
            $days_leads [] = ['day' => $day, 'count' => $count];
            $currentMonthDaysCount += $count;
        }

        foreach ($lastMonthDays as $day) {
            $count = $last_month_leads->get($day)->count ?? 0;
            $last_month_days_leads [] = ['day' => $day, 'count' => $count];
            $lastMonthDaysCount += $count;
        }

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
                })->orwhere('part_ids', '')->orderByRaw('CHAR_LENGTH(name) DESC')->get();

        foreach ($statuses as $status) {
            $status->leads = Client::where('sales_status', $status->name)->where('deleted', 0)->count();
        }

        return view('overview.index', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'last_month_days_leads',
                        'currentMonthDaysCount',
                        'lastMonthDaysCount',
                        'leadsCount',
                        'usersCount',
                        'partsCount',
                        'teamsCount',
                        'rolesCount',
                        'days_leads',
                        'statuses',
                        'api_data',
                        'comments',
                        'period',
                        'teams',
                        'date',
                ));
    }

    public function get_total_financial_data($lead_ids) {
        $api_data['totalWithdrawal'] = 0.00;
        $api_data['totalDeposit'] = 0.00;
        $api_data['ftd_amount'] = 0.00;
        $api_data['ftds'] = [];

        $leads = Client::whereIn('id', $lead_ids)->where('deleted', 0)->where('broker_id', '!=', null)->get();

        foreach ($leads as $index => $lead) {
            if ($lead->broker_id) {
                $MoneyTrxs = MoneyTrx::where('broker_id', $lead->broker_id)->where('status', 'accepted')->select('amount', 'type')->get();
                foreach ($MoneyTrxs as $MoneyTrx) {
                    if ($MoneyTrx->type == 'deposit') {
                        $api_data['totalDeposit'] += $MoneyTrx->amount;
                        $api_data['ftds'][$index] = $MoneyTrx->amount;
                    } elseif ($MoneyTrx->type == 'withdraw') {
                        $api_data['totalWithdrawal'] += $MoneyTrx->amount;
                    }
                }
            }
            $api_data['ftd_amount'] += $api_data['ftds'][$index] ?? 0.00;
        }

        return $api_data;
    }

    public function filter(Request $request) {
        // $mainTpController = new MainTPController;

        $api_data['totalWithdrawal'] = 0.00;
        $api_data['totalDeposit'] = 0.00;
        $api_data['ftd_amount'] = 0.00;
        $api_data['ftds'] = [];
        $from_date = '2020-06-20 00:00:00';
        $to_date = '2035-08-30 00:00:00';

        $leads = Client::where('deleted', 0)->where('broker_id', '!=', null);

        if ($request->users) {
            if ($request->model_type == 'user') {
                $leads->where('user_id', $request->users);
            }
            if ($request->model_type == 'team') {
                $leads->whereHas('user', function ($query) use ($request) {
                    $query->where('team_id', $request->users);
                });
            }
        }

        $leads = $leads->get();

        if ($fromDate = $request->fromTo) {
            $dates = preg_split('/\s*-\s*/', trim($fromDate));

            if (isset($dates[0]) && !empty($dates[0])) {
                $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');
                $from_date = $formattedFromDate . ' 00:00:00';
            }

            if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d');
                $to_date = $formattedToDate . ' 23:59:59';
            } else {
                $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');
                $to_date = $formattedToDate . ' 23:59:59';
            }
        }

        foreach ($leads as $index => $lead) {
            if ($lead->broker_id) {
                $MoneyTrxs = MoneyTrx::where('broker_id', $lead->broker_id)->where('status', 'accepted')->select('amount', 'type')->where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->get();
                foreach ($MoneyTrxs as $MoneyTrx) {
                    if ($MoneyTrx->type == 'deposit') {
                        $api_data['totalDeposit'] += $MoneyTrx->amount;
                        $api_data['ftds'][$index] = $MoneyTrx->amount;
                    } elseif ($MoneyTrx->type == 'withdraw') {
                        $api_data['totalWithdrawal'] += $MoneyTrx->amount;
                    }
                }
            }
            $api_data['ftd_amount'] += $api_data['ftds'][$index] ?? 0.00;
        }

        if ($request->fiter_type == 'FTD') {
            $net = number_format($api_data['ftd_amount'], 2, '.', ',');
        }
        if ($request->fiter_type == 'Deposits') {
            $net = number_format($api_data['totalDeposit'], 2, '.', ',');
        }
        if ($request->fiter_type == 'Withdrawals') {
            $net = number_format($api_data['totalWithdrawal'], 2, '.', ',');
        }

        return $net;
    }

    public function getLastComments($lead_ids = null) {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $pipelineId = Auth::user()->pipeline_id;

        if (!$lead_ids) {
            //$clientsController = new ClientsController;
            //$user_controller   = new UserController;
            //$options = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
            $teams = $this->clientService->getTeams(Auth::user()); //$clientsController->getTeams($options);
            $users = $this->clientService->getUsers($teams, Auth::user()); //$clientsController->getUsers($teams);

            $lead_ids = Client::where(function ($query) use ($users, $isSuperAdmin,$isPipelineAdmin, $pipelineId) {
                        $query->whereIn('user_id', $users->pluck('id'));

                        //if (isset($options['leads_data_show_unassigned_leads'])) {
                        if ($isSuperAdmin || $isPipelineAdmin ||  UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                            $query->orWhere('user_id', null);
                        }
                    })->where('deleted', 0)->pluck('id');
        }

        $comments = Client_comment::whereIn('client_id', $lead_ids)->with(['user', 'client'])->latest()->take(50)->get();

        if (!$lead_ids) {
            return response()->json([
                        'comments' => $comments
            ]);
        }
        return $comments;
    }
}

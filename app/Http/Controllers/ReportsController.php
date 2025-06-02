<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Report;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $clientsController = new ClientsController;
        $user_controller   = new UserController;
        $timePeriod        = $request->input('time-period');
        $user_input        = $request->input('users');
        $employee          = null;
        $options           = $user_controller->get_user_options();
        $month             = null;
        $date              = $request->input('date');
        $year              = null;
        $day               = null;
        $data              = [];
        $teams             = $clientsController->getTeams($options);
        $parts             = $clientsController->getParts($teams);
        $users             = $clientsController->getUsers($teams);

        $statuses = Status::where('pipeline_id', 1)->where(function ($query) use ($parts) {
            foreach ($parts as $part) {
            $query->orWhereJsonContains('part_ids', (string) $part->id);
            }
        })->latest()->get();

        if ($user_input) {
            $employee = User::WithPipeline()->findOrFail($user_input);
        }

        if ($timePeriod == 'yearly') {
            $year = Carbon::createFromFormat('d/m/Y', $date)->format('Y');
        }
        elseif($timePeriod == 'monthly'){
            $year  = Carbon::createFromFormat('d/m/Y', $date)->format('Y');
            $month = Carbon::createFromFormat('d/m/Y', $date)->format('m');
        }
        elseif($timePeriod == 'daily'){
            $day = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        }
        else{
            $day = Carbon::now()->format('Y-m-d');
        }

        $contactsCreated_query = Client::query();
        foreach ($statuses as $status) {
            $statusName = str_replace(' ', '', $status->name);
            ${$statusName . '_query'} = Report::query()->where('type', 'Client')->where('new_status', $status->name);
            if ($employee) {
                ${$statusName . '_query'}->where('modified_by',$user_input);
            }
            if ($month) {
                ${$statusName . '_query'}->whereMonth('created_at',$month);
                $contactsCreated_query->whereMonth('created_at',$month);
            }
            if ($year) {
                ${$statusName . '_query'}->whereYear('created_at',$year);
                $contactsCreated_query->whereYear('created_at',$year);
            }
            if ($day) {
                ${$statusName . '_query'}->whereDate('created_at',$day);
                $contactsCreated_query->whereDate('created_at', $day);
            }
        }


        if ($timePeriod == 'yearly') {
            for ($i = 1; $i <= 12; $i++) {
                $contactsCreated_query = Client::whereYear('created_at', $year)->whereMonth('created_at',$i);
                foreach ($statuses as $status) {
                    $statusName = str_replace(' ', '', $status->name);
                    ${$statusName . '_query'} = Report::query()->where('type','Client')->where('new_status',$status->name)->whereYear('created_at', $year)->whereMonth('created_at',$i);
                }
                
                $statusCounts = [];
                foreach ($statuses as $status) {
                    $statusName = str_replace(' ', '', $status->name);
                    $statusCounts["{$status->name}"] = ${$statusName . '_query'}->count();
                }
                $data[] = array_merge([
                    'contactsCreated' => $contactsCreated_query->count(),
                    'month'           => $i,
                ], $statusCounts);
            }
        }
        $contactsCreated = $contactsCreated_query->count();

        foreach ($statuses as $status) {
            $statusName = str_replace(' ', '', $status->name);
            ${$statusName} = ${$statusName . '_query'}->count();
        }

        return view('reports.index', compact(
            'contactsCreated',
            'timePeriod',
            'employee',
            'statuses',
            'users',
            'date',
            'data',
            'year',
            ...array_reduce($statuses->pluck('name')->toArray(), function ($carry, $statusName) {
            $statusName = str_replace(' ', '', $statusName);
            $carry[] = $statusName;
            return $carry;
            }, [])
        ));
    }
}

@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css?v2.944') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v2.944') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v2.944') }}">
    <style>
        .dcalendarpicker .dudp__wrapper {
            top: 24px !important;
            bottom: unset !important;
        }
        .custom-icons:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section("wrapper")
    <div class="page-wrapper p-2" style="padding-bottom: 20px">
            <div class="page-content">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-5">
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Leads</p>
                                        <h4 class="my-1">{{number_format($leadsCount, 0, '.', ',');}}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-user'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Users</p>
                                        <h4 class="my-1">{{number_format($usersCount, 0, '.', ',');}}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bxs-user-circle'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Parts</p>
                                        <h4 class="my-1">{{number_format($partsCount, 0, '.', ',');}}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-grid-alt'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Teams</p>
                                        <h4 class="my-1">{{number_format($teamsCount, 0, '.', ',');}}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-secondary text-secondary ms-auto"><i class='bx bx-user-pin'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Roles</p>
                                        <h4 class="my-1">{{number_format($rolesCount, 0, '.', ',');}}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bx-sitemap'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-lg-3 row-cols-xl-3">
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <form class="ajax-filter" data-class="FTD" id="FTD-form" name="FTD-form" action="{{ route('overview.filter') }}">
                                    <div class="row">
                                        <div class="col" style="border-right: 1px solid #ced4da">
                                            <input type="text" class="form-control from-to-range" form="no-form" id="ftd_fromTo" placeholder="Select date range">
                                            <input type="hidden" class="rangeDate" name="fromTo">
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <select class="single-select filter-select form-select user-select" name="users" data-typeId="model_type">
                                                    <option value="">All</option>
                                                    @foreach ($teams as $team)
                                                        <option value="{{$team->id}}" data-model="team"><b>{{$team->name}}</b></option>
                                                        <option value="{{$team->leader_id}}" data-model="user">&nbsp;&nbsp;{{$team->leader->username}}</option>
                                                        @foreach ($team->members as $user)
                                                            <option value="{{$user->id}}" data-model="user">&nbsp;&nbsp;{{$user->username}}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="model_type" id="model_type">
                                                <input type="hidden" name="fiter_type" value="FTD">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex align-items-center mt-2">
                                    <div>
                                        <p class="mb-0 text-secondary">Net FTD</p>
                                        <h4 class="my-1 FTD">{{number_format($api_data['ftd_amount'], 2, '.', ',');}} $</h4>
                                    </div>
                                    <button type="submit" form="FTD-form" class="widgets-icons custom-icons bg-light-success text-success ms-auto border-0" style="transition: transform 0.3s;">
                                        <i class='bx bxs-wallet'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <form class="ajax-filter" data-class="Deposits" id="Deposits-form" action="{{ route('overview.filter') }}">
                                    <div class="row">
                                        <div class="col" style="border-right: 1px solid #ced4da">
                                            <input type="text" class="form-control from-to-range" form="no-form" id="Deposits_fromTo" placeholder="Select date range">
                                            <input type="hidden" class="rangeDate" name="fromTo">
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <select class="single-select filter-select form-select user-select" name="users" data-typeId="model_type2">
                                                    <option value="">All</option>
                                                    @foreach ($teams as $team)
                                                        <option value="{{$team->id}}" data-model="team"><b>{{$team->name}}</b></option>
                                                        <option value="{{$team->leader_id}}" data-model="user">&nbsp;&nbsp;{{$team->leader->username}}</option>
                                                        @foreach ($team->members as $user)
                                                            <option value="{{$user->id}}" data-model="user">&nbsp;&nbsp;{{$user->username}}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="model_type" id="model_type2">
                                                <input type="hidden" name="fiter_type" value="Deposits">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex align-items-center mt-2">
                                    <div>
                                        <p class="mb-0 text-secondary">Net Deposits</p>
                                        <h4 class="my-1 Deposits">{{number_format($api_data['totalDeposit'], 2, '.', ',');}} $</h4>
                                    </div>
                                    <button type="submit" form="Deposits-form" class="widgets-icons custom-icons bg-light-warning text-warning ms-auto border-0" style="transition: transform 0.3s;">
                                        <i class='bx bxs-dollar-circle'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <form class="ajax-filter" data-class="Withdrawals" id="Withdrawals-form" action="{{ route('overview.filter') }}">
                                    <div class="row">
                                        <div class="col" style="border-right: 1px solid #ced4da">
                                            <input type="text" class="form-control from-to-range" form="no-form" id="Withdrawals_fromTo" placeholder="Select date range">
                                            <input type="hidden" class="rangeDate" name="fromTo">
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <select class="single-select filter-select form-select user-select" name="users" data-typeId="model_type3">
                                                    <option value="">All</option>
                                                    @foreach ($teams as $team)
                                                        <option value="{{$team->id}}" data-model="team"><b>{{$team->name}}</b></option>
                                                        <option value="{{$team->leader_id}}" data-model="user">&nbsp;&nbsp;{{$team->leader->username}}</option>
                                                        @foreach ($team->members as $user)
                                                            <option value="{{$user->id}}" data-model="user">&nbsp;&nbsp;{{$user->username}}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="model_type" id="model_type3">
                                                <input type="hidden" name="fiter_type" value="Withdrawals">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex align-items-center mt-2">
                                    <div>
                                        <p class="mb-0 text-secondary">Net Withdrawals</p>
                                        <h4 class="my-1 Withdrawals">{{number_format($api_data['totalWithdrawal'], 2, '.', ',');}} $</h4>
                                    </div>
                                    <button type="submit" form="Withdrawals-form" class="widgets-icons custom-icons bg-light-danger text-danger ms-auto border-0" style="transition: transform 0.3s;">
                                        <i class='bx bx-dollar'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0">Leads Analytics</h5>
                                    </div>
                                    <div class="ms-auto">
                                        <form action="" id="monthPickerForm" method="GET"></form>
                                        <label for="subMonth" class="form-label">Select Month</label>
                                        <input type="text" form="monthPickerForm" id="subMonth" name="subMonth" value="{{$date}}" class="form-control monthPicker" onchange="document.getElementById('monthPickerForm').submit();">
                                    </div>
                                </div>
                                <div id="chart19"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-lg-3">
                    <div class="col d-flex">
                        <div class="card radius-10 w-100" style="justify-content: center">
                            <div class="card-contenet">
                                <div class="card-title d-flex align-items-center p-3 m-0">
                                    <div>
                                        @php
                                            $i = 0;
                                            $count = 0;
                                            $totalStatuses = $statuses->count();
                                            $chunkCount = ceil($totalStatuses / 4);
                                            $colors = ['bg-success', 'bg-danger', 'bg-primary', 'bg-warning'];
                                        @endphp
                                        
                                        @foreach ($statuses as $index => $status)
                                            @if ($count == $chunkCount)
                                                @break
                                            @endif
                                            
                                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center rounded">
                                                {{$status->name}}&nbsp;&nbsp;&nbsp;
                                                <div class="ml-auto">
                                                    <span class="badge {{ $colors[$i % 4] }} rounded-pill align-items-end" title="Count">
                                                        {{ number_format($status->leads, 0, '.', ',') }}
                                                    </span>
                                                </div>
                                            </li>
                                            
                                            @php
                                                $i++;
                                                $count++;
                                            @endphp
                                        @endforeach
                                    </div>
                                    
                                    <div class="ms-auto">
                                        <div>
                                            @foreach ($statuses as $index => $status)
                                                @if ($index >= $chunkCount)
                                                    @if ($count == $chunkCount*2)
                                                        @break
                                                    @endif
                                                    
                                                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center rounded">
                                                        {{$status->name}}&nbsp;&nbsp;&nbsp;
                                                        <div class="ml-auto">
                                                            <span class="badge {{ $colors[$i % 4] }} rounded-pill align-items-end" title="Count">
                                                                {{ number_format($status->leads, 0, '.', ',') }}
                                                            </span>
                                                        </div>
                                                    </li>
                                                    
                                                    @php
                                                        $i++;
                                                        $count++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div id="chart15"></div>
                                <div class="card-title d-flex align-items-center p-3 m-0">

                                    <div>
                                        @foreach ($statuses as $index => $status)
                                            @if ($index >= $chunkCount*2)
                                                @if ($count == $chunkCount*3)
                                                    @break
                                                @endif
                                                
                                                <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center rounded">
                                                    {{$status->name}}&nbsp;&nbsp;&nbsp;
                                                    <div class="ml-auto">
                                                        <span class="badge {{ $colors[$i % 4] }} rounded-pill align-items-end" title="Count">
                                                            {{ number_format($status->leads, 0, '.', ',') }}
                                                        </span>
                                                    </div>
                                                </li>
                                                
                                                @php
                                                    $i++;
                                                    $count++;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <div class="ms-auto">
                                        <div>
                                            @foreach ($statuses as $index => $status)
                                                @if ($index >= $chunkCount*3)
                                                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center rounded">
                                                        {{$status->name}}&nbsp;&nbsp;&nbsp;
                                                        <div class="ml-auto">
                                                            <span class="badge {{ $colors[$i % 4] }} rounded-pill align-items-end" title="Count">
                                                                {{ number_format($status->leads, 0, '.', ',') }}
                                                            </span>
                                                        </div>
                                                    </li>
                                                    
                                                    @php
                                                        $i++;
                                                        $count++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-contenet">
                                <div class="d-flex align-items-center p-3">
                                    <div>
                                        <h5 class="mb-0"></h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="card radius-10 w-100">
                            @include("client.last-comments",['comments' => $comments, 'update' => ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_update_comments') ), 'delete' => ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_delete_comments') )])
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
@endsection

@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/apexcharts-bundle/js/apexcharts.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js?v2.944') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-date-time-pickers.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    <script>
        var Statuses = {!! json_encode($statuses) !!};
    </script>
    @if ($period == "Yearly")
        <script>
            var month_sales    = [];
            var month_expenses = [];
        </script>
    @else
        <script>
            var currentMonthDaysCount = {{$currentMonthDaysCount}};
            var lastMonthDaysCount    = {{$lastMonthDaysCount}};
            var last_days_leads       = {!! json_encode($last_month_days_leads) !!};
            var days_leads            = {!! json_encode($days_leads) !!};
        </script>
    @endif
    <script src="{{ url('assets/js/analytics.min.js?v2.944') }}"></script>
@endsection

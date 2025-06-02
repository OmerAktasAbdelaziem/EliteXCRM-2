@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css?v2.944') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endsection

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="card">
                <div class="card-body table-responsive">
                    <form method="GET" action="{{ route('reports.index') }}">
                        <div class="row mb-1">
                            <div class="col-sm-3 form-group">
                                <label for="time-period">Time Period:</label>
                                <select name="time-period" id="time-period" class="form-control">
                                    <option value="daily"   @if ($timePeriod && $timePeriod == 'daily') selected @endif>Daily</option>
                                    <option value="monthly" @if ($timePeriod && $timePeriod == 'monthly') selected @endif>Monthly</option>
                                    <option value="yearly"  @if ($timePeriod && $timePeriod == 'yearly') selected @endif>Yearly</option>
                                </select>
                            </div>
                            <div class="col-sm-3 form-group" id="day-field">
                                <label for="date">Date:</label>
                                <input type="text" name="date" value="@if ($date){{$date}}@else{{\Carbon\Carbon::now()->format('d/m/Y')}}@endif" id="date" class="form-control datetimepicker">
                            </div>
                            <div class="col-sm-3 form-group" id="day-field">
                                <label for="date">Employee:</label>
                                <select class="single-select form-select" name="users">
                                    <option value="" selected>Select employee</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}" @if ($employee && $employee->id == $user->id) selected @endif>{{$user->firstname}} {{$user->lastname}} ({{$user->username}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary px-5">View Report</button>
                    </form>
                    @if ($employee)
                        <br>
                        <div class="h5">
                            Employee : {{$employee->first_name}} {{$employee->last_name}} ({{$employee->username}})
                        </div>
                        <br>
                    @endif
                    @if ($timePeriod && $timePeriod == 'yearly')
                        <div class="table-responsive mt-4">
                            <table class="table align-middle mb-0 table-hover" id="all-reports">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Status</th>
                                        @foreach ($data as $status)
                                            <th scope="col">{{$status['month']}}.Month
                                                <br><em>{{$year}}</em></th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">New Leads</th>
                                        @foreach ($data as $status)
                                            <td>{{ $status['contactsCreated'] }}</td>
                                        @endforeach
                                    </tr>
                                    @foreach ($statuses as $statusItem)
                                        <tr>
                                            <th scope="row">{{$statusItem->name}}</th>
                                            @foreach ($data as $status)
                                                <td>{{ $status[$statusItem->name] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive mt-4">
                            <table class="table align-middle mb-0 table-hover" id="all-reports">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Status</th>
                                        <th scope="row">New Leads</th>
                                        @foreach ($statuses as $item)
                                            <th scope="row">{{$item->name}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Result</th>
                                        <td>{{ $contactsCreated }}</td>
                                        @foreach ($statuses as $status)
                                            @php
                                                $statusName = str_replace(' ', '', $status->name);
                                            @endphp
                                            <td>{{ ${$statusName} }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <h1></h1>
        </div>
    </div>
</div>
@endsection

@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-date-time-pickers.min.js?v2.944') }}"></script>
@endsection
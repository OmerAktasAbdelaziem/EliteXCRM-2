@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
@endsection

@section("wrapper")
    <div class="page-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('fail'))
                            <div class="alert alert-danger">
                                {{ session('fail') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Pipelines</h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                    </div>
                                </div>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle mb-0 table-hover data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Created At</th>
                                                        <th>Parts<br>Used / Available</th>
                                                        <th>Teams<br>Used / Available</th>
                                                        <th>Users<br>Used / Available</th>
                                                        <th>Real Accounts<br>Used / Available</th>
                                                        <th>Demo Accounts <br>Used / Available</th>
                                                        <th>Active Subscription</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($pipelines as $pipeline)
                                                        <tr>
                                                            <td>
                                                                <a @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_view') ) href="{{ route('pipeline.show', $pipeline->id) }}" @endif>{{$pipeline->name}}</a>
                                                            </td>
                                                            <td>{{date('d/m/Y H:i', strtotime($pipeline->created_at))}}</td>
                                                            <td>{{$statistics[$pipeline->id]['currentPartsCount']}} / {{$pipeline->subscription->where('active', 1)->first()->parts_count??'0'}}</td>
                                                            <td>{{$statistics[$pipeline->id]['currentTeamsCount']}} / {{$pipeline->subscription->where('active', 1)->first()->teams_count??'0'}}</td>
                                                            <td>{{$statistics[$pipeline->id]['currentUsersCount']}} / {{$pipeline->subscription->where('active', 1)->first()->users_count??'0'}}</td>
                                                            <td>{{$statistics[$pipeline->id]['currentRealAccountsCount']}} / {{$pipeline->subscription->where('active', 1)->first()->real_accounts??'0'}}</td>
                                                            <td>{{$statistics[$pipeline->id]['currentDemoAccountsCount']}} / {{$pipeline->subscription->where('active', 1)->first()->demo_accounts??'0'}}</td>
                                                            <td>{!! $pipeline->subscription->where('active', 1)->isNotEmpty() ?"<p style='color:green;'>Yes</p>" : "<p style='color:red;'>No</p>" !!}</td>
                                                            <td><a @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_view') ) href="{{ route('pipeline.show', $pipeline->id) }}" @endif><i class="fa-solid fa-pen-to-square"></i></a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section("script")
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection

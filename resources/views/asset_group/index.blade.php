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
                                        <h5 class="mb-1">Our Asset Groups</h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'asset_groups_create'))
                                            <a href="{{ route('assetGroup.create') }}" class="btn btn-success btn-sm">
                                                Add new Asset Group
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle mb-0 table-hover data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Group Name</th>
                                                        <th>Default Group</th>
                                                        <th>Assets Count</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($assetGroups as $assetGroup)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('assetGroup.show', $assetGroup->id) }}">
                                                                    {{$assetGroup->name}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                
                                                            {{ $assetGroup->default == 1 ? 'yes' : 'no' }}
                                                                
                                                            </td>
                                                            <td>
                                                                {{count($assetGroup->asset_ids??[])}}
                                                            </td>
                                                            <td>{{date('d/m/Y H:i', strtotime($assetGroup->created_at))}}</td>
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
@endsection

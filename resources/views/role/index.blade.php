@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
@endsection

@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">Our Roles</h5>
                                </div>
                                <div class="font-22 ms-auto">
                                    @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'roles_create') )
                                    
                                        <a href="{{ route('role.create') }}" class="btn btn-success btn-sm">
                                            Add new role
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
                                                    <th>Role Name</th>
                                                    <!--<th>Number of Users</th>
                                                    <th>Number of Teams</th>
                                                    <th>Number of Parts</th>-->
                                                    <th>Created At</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($roles as $role)
                                                    <tr>
                                                        <td>@if($role->name != 'system_super_admin')<a href="{{ route('role.edit', $role->id) }}">{{$role->name}}</a>@else {{$role->name}} @endif</td>
                                                        <?php /* <td>{{$role->users->count()}}</td>
                                                        <td>{{$role->teams->count()}}</td>
                                                        <td>{{$role->parts->count()}}</td> */ ?>
                                                        <td>{{date('d/m/Y H:i', strtotime($role->created_at))}}</td>
                                                        <td>
                                                            @if($role->name != 'system_super_admin')
                                                            <button type="button" formaction="{{ route('role.delete',$role->id) }}" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                            @endif
                                                        </td>
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

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete selected role?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('delete')
                </form>
                <button type="submit" form="deleteForm" class="btn btn-danger">Delete</button>
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

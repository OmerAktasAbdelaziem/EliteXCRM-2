@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
	<link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
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
                                        <h5 class="mb-1">Users</h5>
                                    </div>
                                    <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#AllContact" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class="bx bx-user-circle font-18 me-1"></i>
                                                </div>
                                                <div class="tab-title">All Users</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#deleted_lead" role="tab" id="deleted_tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class="bx bx-comment-x font-18 me-1"></i>
                                                </div>
                                                <div class="tab-title">Deleted Users</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <form id="addemployee" name="addemployee" method="GET">
                                        @csrf
                                    </form>
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle mb-0 table-hover data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>
                                                            <input class="form-check-input me-3 check-all-table" data-target="check-all-allcontact" type="checkbox">
                                                            Employee Name
                                                        </th>
                                                        <th>Username</th>
                                                        <th>Date of join</th>
                                                        <th>Role</th>
                                                        <th>Email</th>
                                                        <th>Last Login</th>
                                                        <th>Last Logout</th>
                                                        <th>Last seen</th>
                                                        <th class="text-end">
                                                            <button type="button" class="btn btn-sm text-danger text-center w-auto modal-btn" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        @php
                                                            if ($user->lastseen_at != null) {
                                                                if(Carbon\Carbon::parse($user->lastseen_at)->diffInMinutes(Carbon\Carbon::now()) <= 5){
                                                                    $status='online';
                                                                    $status_text='Active';
                                                                }
                                                                else {
                                                                    $status_text='Offline';
                                                                    $status='offline';
                                                                }
                                                            }else {
                                                                $status_text='Offline';
                                                                $status='offline';
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <input class="form-check-input me-3 check-all-allcontact" type="checkbox" form="addemployee" name="userid[]" value="{{$user->id}}" aria-label="...">
                                                                    </div>
                                                                    <div class="user-{{$status}}">
                                                                        <a href="{{ route('user.show', $user->id) }}" >
                                                                            @if ($user->gender && $user->gender == 'Female')
                                                                                <img src="{{  Storage::disk('local')->url($system->femalePic) }}" class="rounded-circle" width="46" height="46" alt="" />
                                                                            @else
                                                                                <img src="{{  Storage::disk('local')->url($system->malePic) }}" class="rounded-circle" width="46" height="46" alt="" />
                                                                            @endif
                                                                        </a>
                                                                    </div>
                                                                    <div class="ms-2">
                                                                        <a href="{{ route('user.show', $user->id) }}"  rel="noopener noreferrer"><h6 class="mb-1 font-14">{{$user->first_name}} {{$user->last_name}}</h6></a>
                                                                        <p class="mb-0 font-13 text-secondary">#{{$user->id}}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{$user->username}}</td>
                                                            <td>{{$user->created_at}}</td>
                                                            <td>
                                                                Part Role : {{ $user->team->part->role->name ?? 'No Role Assigned' }} <br>
                                                                Team Role : {{$user->team?->role?->name}} <br>
                                                                User Role : {{$user->role?->name}} <br>
                                                            </td>
                                                            <td>{{$user->email}}</td>
                                                            <td>{{$user->lastlogin_at}}</td>
                                                            <td>{{$user->lastlogout_at}}</td>
                                                            <td>{{$user->lastseen_at}}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="deleted_lead" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle mb-0 table-hover data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>
                                                            <input class="form-check-input me-3 check-all-table" type="checkbox" data-target="check-all-deleted">
                                                            Employee Name
                                                        </th>
                                                        <th>Username</th>
                                                        <th>Date of join</th>
                                                        <th>Role</th>
                                                        <th>Email</th>
                                                        <th>Last Login</th>
                                                        <th>Last Logout</th>
                                                        <th>Last seen</th>
                                                        <th class="text-end">
                                                            <button type="button" class="btn btn-sm text-primary text-center w-auto modal-btn" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#restoreModal">
                                                                <i class="bx bx-revision"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm text-danger text-center w-auto modal-btn" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteforeverModal">
                                                                <i class="bx bx-trash-alt"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($deleted_users as $deleted_user)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <input class="form-check-input me-3 check-all-deleted" type="checkbox" form="addemployee" name="userid[]" value="{{$deleted_user->id}}" aria-label="...">
                                                                    </div>
                                                                    <div class="deleted_user-offline">
                                                                        <a href="{{ route('user.show', $deleted_user->id) }}" >
                                                                            @if ($deleted_user->gender && $deleted_user->gender == 'Female')
                                                                                <img src="{{  Storage::disk('local')->url($system->femalePic) }}" class="rounded-circle" width="46" height="46" alt="" />
                                                                            @else
                                                                                <img src="{{  Storage::disk('local')->url($system->malePic) }}" class="rounded-circle" width="46" height="46" alt="" />
                                                                            @endif
                                                                        </a>
                                                                    </div>
                                                                    <div class="ms-2">
                                                                        <a href="{{ route('user.show', $deleted_user->id) }}"  rel="noopener noreferrer"><h6 class="mb-1 font-14">{{$deleted_user->first_name}} {{$deleted_user->last_name}}</h6></a>
                                                                        <p class="mb-0 font-13 text-secondary">#{{$deleted_user->id}}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{$deleted_user->username}}</td>
                                                            <td>{{$deleted_user->created_at}}</td>
                                                            <td>
                                                            {{-- Part Role : {{$deleted_user->team?->part->role?->name}} <br>
                                                                Team Role : {{$deleted_user->team?->role?->name}} <br>
                                                                User Role : {{$deleted_user->role?->name}} <br> --}}
                                                            </td>
                                                            <td>{{$deleted_user->email}}</td>
                                                            <td>{{$deleted_user->lastlogin_at}}</td>
                                                            <td>{{$deleted_user->lastlogout_at}}</td>
                                                            <td>{{$deleted_user->lastseen_at}}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'users_delete') )
                                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete selected employees?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="deleteforeverModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete selected employees FOREVER ?!
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger" id="deleteforeverBtn">Delete</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Restoration</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to restore selected employees?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary" id="restoreBtn">Restore</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/apexcharts-bundle/js/apexcharts.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    <script>
        $('#deleteBtn').click(function() {
            $('#addemployee').attr('action', '{{ route('user.delete') }}');
            $('#addemployee').submit();
        });
        $('#deleteforeverBtn').click(function() {
            $('#addemployee').attr('action', '{{ route('user.destroy',1) }}');
            $('#addemployee').submit();
        });
        $('#restoreBtn').click(function() {
            $('#addemployee').attr('action', '{{ route('user.restore') }}');
            $('#addemployee').submit();
        });
    </script>
@endsection

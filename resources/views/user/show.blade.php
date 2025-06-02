@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container">
                <div class="main-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        @if ($user->gender && $user->gender == 'Female')
                                            <img src="{{  Storage::disk('local')->url($system->femalePic) }}" class="rounded-circle" width="100" height="100" alt="" />
                                        @else
                                            <img src="{{  Storage::disk('local')->url($system->malePic) }}" class="rounded-circle" width="100" height="100" alt="" />
                                        @endif
                                        <div class="mt-3">
                                            <h4>{{$user->first_name}} {{$user->last_name}}</h4>
                                        </div>
                                        <small>{{$user->username}}</small>
                                        <span><small class="bx bxs-circle me-1 chart-{{$status}}"></small>{{$status_text}} Now</span>
                                        @if ($status == 'offline' && $user->lastseen_at != null)
                                            <span>Last seen at : {{$user->lastseen_at}}</span>
                                        @endif
                                    </div>
                                    <hr class="my-4" />
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-primary"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> Email</h6>
                                            <span class="text-secondary">{{$user->email}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#view" id="view-tab" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-show font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Information</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#edit" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-edit-alt font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Edit</div>
                                                </div>
                                            </a>
                                        </li>
                                        @if ($user->id != Auth::id())
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#delete" role="tab" aria-selected="false">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class="bx bx-trash font-18 me-1"></i>
                                                        </div>
                                                        <div class="tab-title">Delete</div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    <div class="tab-content py-3">
                                        <div class="tab-pane fade active show" id="view" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <small class="form-label">First Name</small>
                                                    @if ($user->first_name)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->first_name}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Last Name</small>
                                                    @if ($user->last_name)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->last_name}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Username</small>
                                                    @if ($user->username)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->username}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Email Address</small>
                                                    @if ($user->email)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->email}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Role</small>
                                                    @if ($user->role?->name)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->role?->name}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Gender</small>
                                                    @if ($user->gender)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->gender}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Team</small>
                                                    @if ($user->team?->name)
                                                        <h5>
                                                            <span class="input-group-text bg-transparent">
                                                                {{$user->team?->name}}
                                                            </span>
                                                        </h5>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="edit" role="tabpanel">
                                            <form class="row g-3" name="addform" id="addform" method="POST" action="{{ route('user.update',$user->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-6">
                                                    <label for="inputLastName1" class="form-label">First Name</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="inputLastName1" name="first_name" value="{{ $user->first_name }}" placeholder="First Name" required />
                                                    </div>
                                                    @error('first_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputLastName2" class="form-label">Last Name</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="inputLastName2" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name" />
                                                    </div>
                                                    @error('last_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-label">Username</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" placeholder="Last Name" />
                                                    </div>
                                                    @error('username')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" id="inputEmailAddress" name="email" value="{{ $user->email }}" placeholder="Email Address" />
                                                    </div>
                                                    @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Role</label>
                                                    <div class="input-group">
                                                        <select class="form-select single-select" disabled>
                                                            <option value="" selected>Select Role</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{$role->id}}" @if ($user->role_id == $role->id) selected @endif>{{$role->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('role')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Gender</label>
                                                    <div class="input-group">
                                                        <select class="form-select single-select" name="gender">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="Male"   @if ($user->gender == 'Male')   selected @endif>Male</option>
                                                            <option value="Female" @if ($user->gender == 'Female') selected @endif>Female</option>
                                                        </select>
                                                    </div>
                                                    @error('gender')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Team</label>
                                                    <div class="input-group">
                                                        <select class="form-select single-select" name="team_id">
                                                            <option value="" selected>Select Team</option>
                                                            @foreach ($teams as $team)
                                                                <option value="{{$team->id}}" @if ($user->team_id == $team->id) selected @endif>{{$team->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('team_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control password" autocomplete="new-password" id="password" name="password" value="@if ($user->text){{ $user->text->text }}@endif" placeholder="Ex.'Elite@1223'" required/>
                                                        <button class="btn generate-password" style="border:1px solid #ced4da;" title="Generate Password" type="button"><i class='bx bx-reset'></i></button>
                                                        <button type="button" class="bg-transparent border-0 outline-0" onclick="togglePassword()" style="outline: 0"><span class="input-group-text bg-transparent"><i id="passicon" class='bx bx-show' ></i></span></button>
                                                    </div>
                                                    @error('password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="channel_name" class="form-label">Name In Channel</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="channel_name" name="channel_name" value="{{ $user->channel_name }}"/>
                                                    </div>
                                                    @error('channel_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success px-5">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                        @if ($user->id != Auth::id())
                                            <div class="tab-pane fade" id="delete" role="tabpanel">
                                                <form class="row g-3" action="{{ route('user.delete', $user->id) }}" id="deleteForm" name="deleteform">
                                                    @csrf
                                                    <div class="col-12">
                                                        <br>
                                                        Click the button below to delete this employee<br>
                                                        <br>
                                                        <button type="button" class="btn btn-danger px-5" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                                    </div>
                                                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this employee?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
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
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var passicon      = document.getElementById("passicon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passicon.classList.remove("bx-show");
                passicon.classList.add("bxs-show");
            } else {
                passwordField.type = "password";
                passicon.classList.remove("bxs-show");
                passicon.classList.add("bx-show");
            }
        }
    </script>
@endsection
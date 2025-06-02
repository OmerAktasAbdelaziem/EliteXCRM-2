@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-9 mx-auto mt-2">
                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bxs-user me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">User Registration</h5>
                            </div>
                            <hr>
                            <form class="row g-3" name="addform" id="addform" method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputLastName1" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputLastName1" name="first_name" value="{{ old('first_name') }}" form="addform" placeholder="First Name" required />
                                    </div>
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputLastName2" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputLastName2" name="last_name" value="{{ old('last_name') }}" form="addform" placeholder="Last Name" />
                                    </div>
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" form="addform" placeholder="Username" required />
                                    </div>
                                    @error('username')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="inputEmailAddress" name="email" value="{{ old('email') }}" form="addform" placeholder="Email Address" />
                                    </div>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Choose Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control password" id="password" value="{{ old('password') }}" name="password" form="addform" placeholder="Ex.'Elite@1223'" autocomplete="new-password" required/>
                                        <button class="btn generate-password" style="border:1px solid #ced4da;" title="Generate Password" type="button"><i class='bx bx-reset'></i></button>
                                        <button type="button" class="bg-transparent border-0 outline-0" onclick="togglePassword()" style="outline: 0"><span class="input-group-text bg-transparent"><i id="passicon" class='bx bx-show' ></i></span></button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="inputConfirmPassword" value="{{ old('password_confirmation') }}" name="password_confirmation" form="addform" placeholder="Confirm Password" autocomplete="new-password" required />
                                    </div>
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <div class="input-group">
                                        <select class="form-select single-select" id="gender" name="gender" form="addform">
                                            <option value="" selected>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
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
                                                <option value="{{$team->id}}" @if (old('team_id') == $team->id) selected @endif>{{$team->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('team_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="channel_name" class="form-label">Name In Channel</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="channel_name" name="channel_name" value=""/>
                                    </div>
                                    @error('channel_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" form="addform" class="btn btn-danger px-5">Register</button>
                                </div>
                            </form>
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
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
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
    <script>
        var gender = "{{old('gender')}}";

        var select = document.getElementById("gender");
        var options = select.options;

        for (var i = 0; i < options.length; i++) {
            if (options[i].value === gender) {
                select.selectedIndex = i;
                break;
            }
        }
    </script>
@endsection
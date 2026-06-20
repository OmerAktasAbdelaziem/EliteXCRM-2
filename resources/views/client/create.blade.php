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
                                <h5 class="mb-0 text-danger">Lead Registration</h5>
                            </div>
                            <hr>
                            <form class="row g-3" action="{{ route('client.store') }}" method="POST" name="addform" id="addform">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputLastName1" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" id="inputLastName1" placeholder="First Name" required />
                                    </div>
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputLastName2" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputLastName2" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required />
                                    </div>
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputPhoneNo" class="form-label">Primary Number</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="inputPhoneNo" name="phone1" value="{{ old('phone1') }}" placeholder="Primary Number" required />
                                    </div>
                                    @error('phone1')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputPhoneNo2" class="form-label">Secondary Number</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="inputPhoneNo2" name="phone2" value="{{ old('phone2') }}" placeholder="Secondary Number" />
                                    </div>
                                    @error('phone2')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <input type="mail" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required />
                                    </div>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country</label>
                                    <div class="input-group">
                                        <select class="single-select form-select" name="country">
                                            @foreach(config('countries') as $code => $name)
                                                <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sales Status</label>
                                    <div class="input-group">
                                        <select class="single-select form-select" name="sales_status">
                                            <option value="" >Select Status</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{$status->name}}" @if (old('sales_status') == $status->name) selected @endif>{{$status->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('sales_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="source" class="form-label">Source</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="source" name="source" value="{{ old('source') }}" placeholder="Source" />
                                    </div>
                                    @error('source')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Assigned User</label>
                                    <div class="input-group">
                                        <select class="single-select form-select" name="user_id">
                                            <option value="" >Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}" @if (old('user_id') == $user->id) selected @endif>{{$user->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger px-5">Register</button>
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
        var country = "{{old('country')}}";

        var select = document.getElementById("country");
        var options = select.options;

        for (var i = 0; i < options.length; i++) {
            if (options[i].value === country) {
                select.selectedIndex = i;
                break;
            }
        }
    </script>
@endsection



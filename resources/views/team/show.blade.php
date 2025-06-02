@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
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
                <div class="col-xl-9 mx-auto mt-2">
                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bxs-user-pin me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">
                                    @if ($team->getKey())
                                    Team Edit
                                    @else
                                    Team Registration
                                    @endif
                                </h5>
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $team->getKey()?route('team.update',$team->getKey()):route('team.store') }}">
                                @csrf
                                @if ($team->getKey())
                                    @method('PUT')
                                @endif
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $team->name??old('name') }}" placeholder="Team Name" required />
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="leader" class="form-label">Team Leader</label>
                                    <div class="input-group">
                                        <select class="single-select form-select" id="leader" name="leader_id">
                                            <option value="" selected>Select Leader</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}" @if ($team->leader_id == $user->id) selected @endif >{{$user->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('leader_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="part" class="form-label">Part</label>
                                    <div class="input-group">
                                        <select class="single-select form-select" id="part" name="part_id" required>
                                            <option value="">Select Part</option>
                                            @foreach ($parts as $part)
                                                <option value="{{$part->id}}" @if ($team->part_id == $part->id) selected @endif>{{$part->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('part_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="members" class="form-label">Team Members</label>
                                    <div class="input-group">
                                        <select class="multiple-select form-select" id="members" name="members[]" multiple>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" 
                                                    @if($team->members->contains($user->id)) 
                                                        selected 
                                                    @endif>
                                                    {{ $user->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('members')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    @if ($team->getKey())
                                        <button type="submit" class="btn btn-danger px-5">Update</button>
                                    @else
                                        <button type="submit" class="btn btn-danger px-5">Register</button>
                                    @endif
                                </div>
                            </form>
                        </div>
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
@endsection
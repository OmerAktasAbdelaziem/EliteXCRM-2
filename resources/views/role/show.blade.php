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
                                <div><i class="bx bx-sitemap me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">
                                    @if ($role->getKey())
                                        Role Edit
                                    @else
                                        Role Registration
                                    @endif
                                </h5>
                            </div>
                            <hr>
                            <form class="row g-3" name="addform" id="addform" method="POST" action="{{ $role->getKey()?route('role.update',$role->getKey()):route('role.store') }}">
                                @csrf
                                @if ($role->getKey())
                                    @method('PUT')
                                @endif
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$role->name) }}" placeholder="Role Name" required />
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="teams" class="form-label">Teams</label>
                                    <div class="input-group">
                                        <select class="multiple-select form-select" id="teams" name="teams[]" multiple>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}" 
                                                    @if($role->teams->contains($team->id)) 
                                                        selected 
                                                    @endif>
                                                    {{ $team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('teams')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="users" class="form-label">Users</label>
                                    <div class="input-group">
                                        <select class="multiple-select form-select" id="users" name="users[]" multiple>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" 
                                                    @if (in_array($role->id, explode(',', trim($user->role_ids, '[]'))))
                                                        selected 
                                                    @endif>
                                                    {{ $user->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('users')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="parts" class="form-label">Parts</label>
                                    <div class="input-group">
                                        <select class="multiple-select form-select" id="parts" name="parts[]" multiple>
                                            @foreach ($parts as $part)
                                                <option value="{{ $part->id }}" 
                                                    @if($role->parts->contains($part->id)) 
                                                        selected 
                                                    @endif>
                                                    {{ $part->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('parts')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                @include("role.options",['role' => $role,'parts' => $parts,'teams' => $teams])
                                
                                <div class="col-12">
                                    @if ($role->getKey())
                                        <button type="submit" class="btn btn-danger px-5">Update</button>
                                        <button type="submit" formaction="{{route('role.clone',$role->getKey())}}" class="btn btn-primary px-5">Clone</button>
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
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection
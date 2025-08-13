@extends("layouts.app")

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
                            <div><i class="bx bx-layer me-1 font-22 text-danger"></i>
                            </div>
                            <h5 class="mb-0 text-danger">
                                Subscription Registration
                            </h5>
                        </div>
                        <hr>
                        @if (isset($options['subscription_show']))
                        <form  method="POST" action="{{route('subscription.store')}}">
                            @csrf
                            
                        <input type="hidden" name="pipeline" value="{{$pipelineId}}">
                                <div class ="section-area">
                                    <span class="section-title">Details</span>
                                <div class="row">
                                    <div class="col-md-4">
                                    <label for="parts_count" class="form-label">Allowed Parts</label>
                                    <div class="input-group">
                                        <input type="number" name="parts_count" class="form-control" value="{{ old('parts_count', '0') }}">
                                    </div>
                                    @error('parts_count')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-4">
                                    <label for="teams_count" class="form-label">Allowed Teams</label>
                                    <div class="input-group">
                                        <input type="number" name="teams_count" class="form-control" value="{{ old('teams_count', '0') }}">
                                    </div>
                                    @error('teams_count')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-4">
                                    <label for="users_count" class="form-label">Allowed Users</label>
                                    <div class="input-group">
                                        <input type="number" name="users_count" class="form-control" value="{{ old('users_count', '0') }}">
                                    </div>
                                    @error('users_count')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                   
                                     <div class="col-md-4">
                                    <label for="real_accounts" class="form-label">Allowed Real Accounts</label>
                                    <div class="input-group">
                                        <input type="number" name="real_accounts" class="form-control" value="{{ old('real_accounts', '0') }}">
                                    </div>
                                    @error('real_accounts')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                     <div class="col-md-4">
                                    <label for="demo_accounts" class="form-label">Allowed Demo Accounts</label>
                                    <div class="input-group">
                                        <input type="number" name="demo_accounts" class="form-control" value="{{ old('demo_accounts', '0') }}">
                                    </div>
                                    @error('demo_accounts')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-4">
                                    <label for="active" class="form-label">Active</label>
                                    <div class="input-group">
                                        <input type="checkbox" name="active"  value="1" {{ old('active') !== null ? (old('active') == 1 ? 'checked' : '') : 'checked' }}>
                                    </div>
                                    @error('active')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                </div>
                                </div>
                            
                            
                                <div class ="section-area">
                                    <span class="section-title">Duration</span>
                                <div class="row">
                                    
                                
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <div class="input-group">
                                        <input type="datetime-local" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                                    </div>
                                    @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <div class="input-group">
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                    </div>
                                    @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                </div>
                                
                                    </div>
                    
                                
                              
<button type="submit" class="btn btn-danger px-5">Register</button>
                            </form>
                    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</div>

@endsection
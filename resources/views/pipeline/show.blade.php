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
                            <div><i class="bx bx-layer me-1 font-22 text-danger"></i>
                            </div>
                            <h5 class="mb-0 text-danger">
                                @if ($pipeline->getKey())
                                Pipeline Edit
                                @else
                                Pipeline Registration
                                @endif
                            </h5>
                        </div>
                        <hr>


                        <form  method="POST" action="{{ $pipeline->getKey()?route('pipeline.update',$pipeline->getKey()):route('pipeline.store') }}">
                            @csrf
                            <p><h3>General info </h3></p>
                            <div class ="section-area">
                                <div class="row g-3">
                                    @if ($pipeline->getKey())
                                    @method('PUT')
                                    @endif
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$pipeline->name) }}" placeholder="Pipeline Name" required />
                                        </div>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <div class="input-group">
                                            <select class="form-select single-select" id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach ([1 => 'Trade',2 => 'Media'] as $category_id => $category_name)
                                                <option value="{{ $category_id }}" @if($pipeline->category_id == $category_id) selected @endif>
                                                    {{ $category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="broker_id" class="form-label">Broker</label>
                                        <div class="input-group">
                                            <select class="form-select single-select" id="broker_id" name="broker_id">
                                                <option value="">Select Broker</option>
                                                @foreach ($brokers as $broker)
                                                <option value="{{ $broker->id }}" @if($pipeline->broker_id == $broker->id) selected @endif>
                                                    {{ $broker->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('broker_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <p><h3>Users Details </h3></p>
                            <div class ="section-area">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="part_limit" class="form-label">Parts Limitation</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="part_limit" name="part_limit" value="{{ old('part_limit',$pipeline->part_limit) }}" placeholder="Enter Number" />
                                        </div>
                                        @error('part_limit')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="team_limit" class="form-label">Teams Limitation</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="team_limit" name="team_limit" value="{{ old('team_limit',$pipeline->team_limit) }}" placeholder="Enter Number" />
                                        </div>
                                        @error('team_limit')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="user_limit" class="form-label">User Limitation</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="user_limit" name="user_limit" value="{{ old('user_limit',$pipeline->user_limit) }}" placeholder="Enter Number" />
                                        </div>
                                        @error('user_limit')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <p><h3>Support Details </h3></p>
                            <div class ="section-area">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="support_ids" class="form-label">Support Users</label>
                                        <div class="input-group">
                                            <select class="multiple-select form-select" id="support_ids" name="support_ids[]" multiple>
                                                @foreach ($users as $user)
                                                <option value="{{ $user->id }}" 
                                                        @if(in_array($user->id, $pipeline->support_ids??[])) selected @endif>{{ $user->username }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('support_ids')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="co_id" class="form-label">Co Admin</label>
                                        <div class="input-group">
                                            <select class="form-select single-select" id="co_id" name="co_id">
                                                <option value="">Select User</option>
                                                @foreach ($users as $user)
                                                <option value="{{ $user->id }}" 
                                                        @if($pipeline->co_id == $user->id) selected @endif>{{ $user->username }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('co_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if (isset($options['subscription_show']) && $pipeline->getKey())
                            <p><h3>Supscription Details </h3></p>
                            <div class ="section-area">
                                <a href="{{ route('subscription.create', ['pipelineId' => $pipeline->getKey()]) }}" type="button" class="btn btn-primary">Add New</a>
                                <div class="row g-12">
                                    <table id="subscriptions" class="display">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Parts Count</th>
                                                <th>Teams Count</th>
                                                <th>Users Count</th>
                                                <th>Real Accounts</th>
                                                <th>Demo Account</th>
                                                <th>Active</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($supscriptions as $supscription)
                                            <tr>
                                                <td>{{$supscription->start_date}}</td>
                                                <td>{{$supscription->end_date}}</td>
                                                <td>{{$supscription->parts_count}}</td>
                                                <td>{{$supscription->teams_count}}</td>
                                                <td>{{$supscription->users_count}}</td>
                                                <td>{{$supscription->real_accounts}}</td>
                                                <td>{{$supscription->demo_accounts}}</td>
                                                <td>{!!$supscription->active == 1? "<p style='color:green;'>Yes</p>" : "<p style='color:red;'>No</p>"!!}</td>
                                                <td>
                                                    @if (isset($options['subscription_update']))<a  href="{{ route('subscription.edit', $supscription->id) }}" ><i class="fa-solid fa-pen-to-square"></i></a>@endif   
                                                    @if (isset($options['subscription_delete']))
                                                    <a href="{{ route('subscription.destroy', $supscription->id) }}"
                                                       onclick="return confirm('Are you sure you want to delete this subscription?')">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            @endif
                            <div class="col-12">
                                @if ($pipeline->getKey())
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
    </div>
</div>
@endsection

@section("script")
<script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
<script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
<script>
     
       $(document).ready(function() {
    $('#subscriptions').DataTable({
        order: [
            [0, 'desc'],  
            [1, 'desc']  
        ],
       
        columnDefs: [
            { orderable: false, targets: -1 } // Lastt column not allowed to be ordered
        ]
    });
});
</script>
@endsection
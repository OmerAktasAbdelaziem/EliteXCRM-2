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
                            <div class="d-flex justify-content-between">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bx-shield-quarter me-1 font-22 text-danger"></i></div>
                                    <h5 class="mb-0 text-danger">
                                        IP Restriction
                                    </h5>                                    
                                </div>
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ route('ip-restrict.store') }}">
                                @csrf

                                 <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-bold mb-0">Allowed IP Addresses</label>
                                        <button type="button" class="btn btn-outline-success btn-sm px-3" onclick="addNewIpField()">
                                            <i class="bx bx-plus me-1"></i> Add Another IP
                                        </button>
                                    </div>
                                    
                                    <div id="ip-wrapper" class="d-flex flex-column gap-2 mb-2">
                                        @forelse($currentIps as $index => $ipRow)
                                            <div class="ip-row input-group">
                                                <span class="input-group-text bg-light text-muted">
                                                    <i class="bx bx-globe"></i>
                                                </span>
                                                <input type="text" class="form-control" name="ip_addresses[]" value="{{ $ipRow->ip_address }}" placeholder="e.g. 192.168.1.1">
                                                {{-- @if($index > 0) --}}
                                                    <button type="button" class="btn btn-outline-danger" onclick="removeIpField(this)">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                {{-- @endif --}}
                                            </div>
                                        @empty
                                            <div class="ip-row input-group">
                                                <span class="input-group-text bg-light text-muted">
                                                    <i class="bx bx-globe"></i>
                                                </span>
                                                <input type="text" class="form-control" name="ip_addresses[]" placeholder="e.g. 192.168.1.1">
                                                <button type="button" class="btn btn-outline-danger" onclick="removeIpField(this)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        @endforelse
                                    </div>    

                                    @error('ip_addresses')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    {{-- <div class="form-text text-muted">Leave empty to completely disable IP filtering for this pipeline.</div> --}}
                                </div>

                                
                                <div class="col-md-12">

                                    <label for="exempted_users" class="form-label">Exempted Pipeline Users (Can bypass IP security)</label>
                                    <div class="input-group">
                                        <select class="multiple-select form-select" id="exempted_users" name="exempted_users[]" multiple>
                                            @foreach ($pipelineUsers as $user)
                                                <option value="{{$user->id}}"  {{ in_array($user->id, $exemptedUsers) ? 'selected' : '' }}>{{$user->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('exempted_users')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger px-5">Save Protection Settings</button>
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
    <script>
        function addNewIpField() {
            let wrapper = document.getElementById('ip-wrapper');
            let div = document.createElement('div');
            div.className = 'ip-row input-group';
            
            // Build the polished input layout complete with global icon and a delete trash trigger button
            div.innerHTML = `
                <span class="input-group-text bg-light text-muted">
                    <i class="bx bx-globe"></i>
                </span>
                <input type="text" class="form-control" name="ip_addresses[]" placeholder="e.g. 192.168.1.1">
                <button type="button" class="btn btn-outline-danger" onclick="removeIpField(this)">
                    <i class="bx bx-trash"></i>
                </button>
            `;
            
            wrapper.appendChild(div);
            // Autofocus the newly created row field
            div.querySelector('input').focus();
        }

        function removeIpField(button) {
            // Traverse up to find the surrounding row wrapper container to remove safely
            let row = button.closest('.ip-row');
            row.remove();
        }
    </script>

    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection
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
                                <div><i class="bx bx-mail-send me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">
                                    @if ($senderEmail->getKey())
                                        Sender Email Edit
                                    @else
                                    Sender Email Registration
                                    @endif
                                </h5>
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $senderEmail->getKey()?route('sender_emails.update',$senderEmail->getKey()):route('sender_emails.store') }}">
                                @csrf
                                @if ($senderEmail->getKey())
                                    @method('PUT')
                                @endif
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="email" name="email" value="{{ old('email',$senderEmail->email) }}" placeholder="Enter Email" required />
                                    </div>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" autocomplete="new-username" id="username" name="username" value="{{ old('username',$senderEmail->username) }}" placeholder="Enter Email Username" required />
                                    </div>
                                    @error('username')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" autocomplete="new-password"  id="password" name="password" value="{{ old('password',$senderEmail->password) }}" placeholder="Enter Email Password" required />
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name',$senderEmail->company_name) }}" placeholder="Enter Company Name" required />
                                    </div>
                                    @error('company_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="host" class="form-label">SMTP Host</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="host" name="host" value="{{ old('host',$senderEmail->host) }}" placeholder="Enter Email Host" required />
                                    </div>
                                    @error('host')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="port" class="form-label">SMTP Port</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="port" name="port" value="{{ old('port',$senderEmail->port) }}" placeholder="Enter Email Port" required />
                                    </div>
                                    @error('port')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="encryption" class="form-label">Encryption Type</label>
                                    <div class="input-group">
                                        <select class="form-control" name="encryption" id="encryption" required>
                                            <option value="SSL">SSL</option>
                                            <option value="TLS">TLS</option>
                                            <option value="STARTTLS">STARTTLS</option>
                                        </select>
                                    </div>
                                    @error('encryption')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    @if ($senderEmail->getKey())
                                    @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'emails_sender_email_update') )
                                            <button type="submit" class="btn btn-primary px-5">Update</button>
                                        @endif
                                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'emails_sender_email_update') )
                                            <button type="button" class="btn btn-danger px-5" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                        @endif
                                    @else
                                        <button type="submit" class="btn btn-success px-5">Add Sender Email</button>
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
    @if ($senderEmail->getKey() && ($senderEmail->getKey() && (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'emails_sender_email_delete') ) ))
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Email?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('sender_emails.delete', $senderEmail->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection
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
                                        <h5 class="mb-1">Sender Emails</h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'emails_sender_email_create') )
                                        
                                            <a href="{{ route('sender_emails.create') }}" class="btn btn-success btn-sm">
                                                Add new Sender Email
                                            </a>
                                        @endif
                                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'emails_template_list') )
                                        
                                            <a href="{{ route('emails.index') }}" class="btn btn-primary btn-sm">
                                                Email Templates
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle mb-0 table-hover data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Email</th>
                                                        <th>Company Name</th>
                                                        <th>Host</th>
                                                        <th>Port</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($senderEmails as $senderEmail)
                                                        <tr>
                                                            <td>
                                                                <a @if(UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'sender_email_show') ) href="{{ route('sender_emails.show', $senderEmail->id) }}" @endif>
                                                                    {{$senderEmail->email}}
                                                                </a>
                                                            </td>
                                                            <td>{{$senderEmail->company_name}}</td>
                                                            <td>{{$senderEmail->host}}</td>
                                                            <td>{{$senderEmail->port}}</td>
                                                            <td>{{date('d/m/Y H:i', strtotime($senderEmail->created_at))}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection

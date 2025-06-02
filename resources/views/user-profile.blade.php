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
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ route('user.edit', 1) }}" class="hidden" method="GET" id="editform" name="editform">
                                                @csrf
                                            </form>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="form-label">First Name</small>
                                                    <h5>
                                                        <span class="input-group-text bg-transparent">
                                                            <i class='bx bxs-user'></i> &nbsp;
                                                            {{Auth::user()->first_name}}
                                                        </span>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Last Name</small>
                                                    <h5>
                                                        <span class="input-group-text bg-transparent">
                                                            <i class='bx bxs-user'></i> &nbsp;
                                                            {{Auth::user()->last_name}}
                                                        </span>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Username</small>
                                                    <h5>
                                                        <span class="input-group-text bg-transparent">
                                                            <i class='bx bx-user-pin'></i> &nbsp;
                                                            {{Auth::user()->username}}
                                                        </span>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="form-label">Email</small>
                                                    <h5>
                                                        <span class="input-group-text bg-transparent">
                                                            <i class='bx bx-mail-send'></i> &nbsp;
                                                            {{Auth::user()->email}}
                                                        </span>
                                                    </h5>
                                                </div>
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
        @endsection




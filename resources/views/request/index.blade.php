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
                    <div class="col-xl-12 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Our Requests</h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                        <button type="button" formaction="{{ route('main_tp.multi_handle_request', ['status' => 'accepted']) }}" data-bs-toggle="modal" data-bs-target="#multiHandleRequestModal" class="btn btn-success text-white text-center w-auto modal-btn mx-1 handleForm">
                                            <span class="number">0</span>
                                            Accept
                                        </button>
                                        <button type="button" formaction="{{ route('main_tp.multi_handle_request', ['status' => 'rejected']) }}" data-bs-toggle="modal" data-bs-target="#multiHandleRequestModal" class="btn btn-danger text-white text-center w-auto modal-btn mx-1 handleForm">
                                            <span class="number">0</span>
                                            Reject
                                        </button>
                                    </div>
                                </div>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle data-table-created mb-0 table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="max-width: 20px">
                                                            <input class="form-check-input me-3 check-all-table" data-target="check-request" type="checkbox">
                                                        </th>
                                                        <th>Client ID</th>
                                                        <th>Name</th>
                                                        <th>ID</th>
                                                        <th>Amount</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Comment</th>
                                                        <th>Payment Details</th>
                                                        <th>Receipt</th>
                                                        <th>Date/Time</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($request_data as $request)
                                                        <tr>
                                                            <td style="max-width: 20px">
                                                                <input class="form-check-input me-3 check-request check-number" type="checkbox" form="handleForm" name="request_ids[]" value="{{$request->id}}" aria-label="...">
                                                            </td>
                                                            <td>
                                                                
                                                                @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_lead_id_show') )
                                                                
                                                                    <a @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'leads_show') ) href="{{ route('client.show', ['client' => $request->client->id , 'status' => $request->client->sales_status]) }}" @endif rel="noopener noreferrer">
                                                                        #{{$request->client->id}}
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="ms-2">
                                                                    <a @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'leads_show') ) href="{{ route('client.show', ['client' => $request->client->id , 'status' => $request->client->sales_status]) }}" @endif rel="noopener noreferrer">
                                                                        <h6 class="mb-1 font-14">
                                                                            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_show') )
                                                                            
                                                                                {{$request->client->first_name}}
                                                                            @elseif ( UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_hide') )
                                                                                <div>
                                                                                    {{ substr($request->client->first_name, 0, ceil(strlen($request->client->first_name) / 2)) }}******
                                                                                </div>
                                                                            @else
                                                                                <div>
                                                                                    ******
                                                                                </div>
                                                                            @endif
                                                                            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_show') )
                                                                                {{$request->client->last_name}}
                                                                            @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_hide') )
                                                                                <div>
                                                                                    {{ substr($request->client->last_name, 0, ceil(strlen($request->client->last_name) / 2)) }}******
                                                                                </div>
                                                                            @else
                                                                                <div>
                                                                                    ******
                                                                                </div>
                                                                            @endif
                                                                        </h6>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{ $request->id }}
                                                            </td>
                                                            <td>
                                                                {{number_format($request->amount, 2, '.', ',');}}
                                                            </td>
                                                            <td class="text-success">
                                                                {{ ucwords($request->type) }}
                                                            </td>
                                                            <td>
                                                                Pending
                                                            </td>
                                                            <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                {!! $request->comment !!}
                                                            </td>
                                                            <td>
                                                                @if ($request->bank_details)
                                                                    Iban                : {{$request->bank_details['iban'] ?? ''}}
                                                                    <br>
                                                                    Swift               : {{$request->bank_details['swift'] ?? ''}}
                                                                    <br>
                                                                    Currency            : {{$request->bank_details['currency'] ?? ''}}
                                                                    <br>
                                                                    Bank Name           : {{$request->bank_details['bank_name'] ?? ''}}
                                                                    <br>
                                                                    Account Number      : {{$request->bank_details['account_number'] ?? ''}}
                                                                    <br>
                                                                    Account Holder Name : {{$request->bank_details['account_holder_name'] ?? ''}}
                                                                    <br>
                                                                    Swift Code           : {{$request->bank_details['swift_code'] ?? ''}}
                                                                    <br>
                                                                    Bank Country        : {{$request->bank_details['bank_country'] ?? ''}}
                                                                    <br>
                                                                    Bank Address        : {{$request->bank_details['bank_address'] ?? ''}}
                                                                    <br>
                                                                    Beneficiary Name    : {{$request->bank_details['beneficiary_name'] ?? ''}}
                                                                    <br>
                                                                    Beneficiary Address : {{$request->bank_details['beneficiary_address'] ?? ''}}
                                                                    <br>
                                                                    ABA Routing Number  : {{$request->bank_details['aba_routing_number'] ?? ''}}
                                                                    <br>
                                                                    Beneficiary Country : {{$request->bank_details['beneficiary_country'] ?? ''}}
                                                                @endif
                                                                @if ($request->bank_id)
                                                                    Bank Name           : {{$request->bank->name??''}}
                                                                    <br>
                                                                    Bank Country        : {{$request->bank->country??''}}
                                                                @endif
                                                                {{$request->usdt}}
                                                                {{$request->note ?? ''}}
                                                            </td>
                                                            <td>
                                                                @if ($request->receipt)
                                                                    <a href="{{$request->receipt}}" class="btn btn-sm w-auto" style="background-color: transparent" download >
                                                                        <i class="bx bx-download"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ date('d/m/Y H:i', strtotime($request->created_at)) }}
                                                            </td>
                                                            <td>
                                                                <button type="button" formaction="{{ route('main_tp.handle_request', ['id' => $request->id,'status' => 'accepted']) }}" class="btn btn-sm text-success text-center w-auto deleteForm" data-bs-toggle="modal" data-bs-target="#handleRequestModal" style="background-color: transparent">
                                                                    Accept
                                                                </button>
                                                                <button type="button" formaction="{{ route('main_tp.handle_request', ['id' => $request->id,'status' => 'rejected']) }}" class="btn btn-sm text-danger text-center w-auto deleteForm" data-bs-toggle="modal" data-bs-target="#handleRequestModal" style="background-color: transparent">
                                                                    Reject
                                                                </button>
                                                            </td>
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
        <div class="modal fade" id="EditWithdrawRequestModal" tabindex="-1" aria-labelledby="EditWithdrawRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditWithdrawRequestModalLabel">Update Withdraw Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="" method="POST" id="EditWithdrawRequest">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <label for="editAmount" class="form-label">Amount</label>
                                        <input type="number" id="editAmount" name="amount" value="0.01" step="any" class="form-control" placeholder="Amount" required/>
                                    </div>
                                    @if (auth()->user()->pipeline->broker_id == 1)    
                                        <div class="col-md-6 mt-2">
                                            <label for="branchId" class="form-label">Branch</label>
                                            <div class="input-group">
                                                <select id="branchId" class="single-select form-select inside-modal" name="branchId" required>
                                                    @foreach ($branch_data as $branch)
                                                        <option value="{{$branch['id']}}">{{$branch['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12 mt-2">
                                        <label for="withdrawComment" class="form-label">Comment</label>
                                        <textarea rows="3" id="withdrawComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </div>
            </div>
        </div>
    
        <div class="modal fade" id="EditDepositRequestModal" tabindex="-1" aria-labelledby="EditDepositRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditDepositRequestModalLabel">Update Deposit Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="" method="POST" id="EditDepositRequest">
                                @csrf
                                @method('PUT')
                                <div class="row" id="open_order_section">
                                    <div class="col-md-6 mt-2">
                                        <label for="editDepositAmount" class="form-label">Amount</label>
                                        <input type="number" id="editDepositAmount" name="amount" value="0.01" step="any" class="form-control" placeholder="Amount" required/>
                                    </div>
                                    @if (auth()->user()->pipeline->broker_id == 1)
                                        <div class="col-md-6 mt-2">
                                            <label for="bankId" class="form-label">Bank</label>
                                            <div class="input-group">
                                                <select id="bankId" class="single-select form-select inside-modal" name="bankId" required>
                                                    @foreach ($bank_data as $bank)
                                                        <option value="{{$bank['id']}}">{{$bank['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12 mt-2">
                                        <label for="depositComment" class="form-label">Comment</label>
                                        <textarea rows="3" id="depositComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="handleRequestModal" tabindex="-1" aria-labelledby="handleRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="handleRequestModalLabel">Handle Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="deleteForm" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="reqComment" class="form-label">Comment</label>
                                        <textarea rows="3" id="reqComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="multiHandleRequestModal" tabindex="-1" aria-labelledby="multiHandleRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="multiHandleRequestModalLabel">Multi Handle Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="handleForm" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="multiReqComment" class="form-label">Comment</label>
                                        <textarea rows="3" id="multiReqComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </div>
            </div>
        </div>
@endsection

@section("script")
    <script>
        var pipeline_broker_id = {{ auth()->user()->pipeline->broker_id }};
        var broker_id = 'null';
    </script>
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/main_tp.min.js?v2.944') }}"></script>
@endsection

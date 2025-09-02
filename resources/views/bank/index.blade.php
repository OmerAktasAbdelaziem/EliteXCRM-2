@extends("layouts.app")
@section("style")
<link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
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
                                <h5 class="mb-1">Our Banks</h5>
                            </div>
                            <div class="font-22 ms-auto">
                                <div class="d-flex justify-content-end">
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'banks_create'))
                                    <a href="{{ route('bank.create') }}" class="btn btn-success btn-sm">
                                        Add new bank
                                    </a>
                                    @endif
                                    <a class="btn btn-primary btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#usdtModal">
                                        USDT Address
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                <div class="table-responsive mt-4">
                                    <table class="table align-middle mb-0 table-hover data-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Country</th>
                                                <th>Type</th>
                                                <th>address</th>
                                                <th>Beneficiary Name</th>
                                                <th>Beneficiary Country</th>
                                                <th>Beneficiary Address</th>
                                                <th>ABA Routing Number</th>
                                                <th>Iban</th>
                                                <th>Swift Code</th>
                                                <th>Account Number</th>
                                                <th>Bic</th>
                                                <th>Active</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($banks as $bank)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('bank.show', $bank->id) }}">
                                                        {{$bank->name}}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{$bank->country}}
                                                </td>
                                                <td>
                                                    {{$bank->type}}
                                                </td>
                                                <td>
                                                    {{$bank->address}}
                                                </td>
                                                <td>
                                                    {{$bank->beneficiary_name}}
                                                </td>
                                                <td>
                                                    {{$bank->beneficiary_country}}
                                                </td>
                                                <td>
                                                    {{$bank->beneficiary_address}}
                                                </td>
                                                <td>
                                                    {{$bank->aba_routing_number}}
                                                </td>
                                                <td>
                                                    {{$bank->iban}}
                                                </td>
                                                <td>
                                                    {{$bank->swift_code}}
                                                </td>
                                                <td>
                                                    {{$bank->account_number}}
                                                </td>
                                                <td>
                                                    {{$bank->bic}}
                                                </td>
                                                <td>
                                                    <i class="bx bx-{{$bank->is_active ? 'check text-success' : 'x text-danger'}}" style="font-size: 22px"></i>
                                                </td>
                                                <td>{{date('d/m/Y H:i', strtotime($bank->created_at))}}</td>
                                                <td>
                                                    <button type="button" formaction="{{ route('bank.delete',$bank->id) }}" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="bx bx-trash"></i>
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


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete selected Bank?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST"  id="deleteForm">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="submit" form="deleteForm" class="btn btn-danger">Delete</button>
            </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="usdtModal" tabindex="-1" aria-labelledby="usdtModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usdtModalLabel">Default USDT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('usdt.update', ['id'=>auth()->user()->pipeline_id]) }}" id="usdtForm" class="d-none" method="POST">
                    @csrf
                    @method('PUT')
                </form>
                <div class="row g-2">
                    <div class="col-12">
                        <label for="usdt" class="form-label">USDT Phoenix</label>
                        <input type="text" name="usdt[phoenix]" class="form-control" form="usdtForm" id="usdt" value="{{auth()->user()->pipeline->usdt['phoenix']??''}}">
                    </div>
                    <div class="col-12">
                        <label for="usdt" class="form-label">USDT BNC</label>
                        <input type="text" name="usdt[BNC]" class="form-control" form="usdtForm" id="usdt" value="{{auth()->user()->pipeline->usdt['BNC']??''}}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" form="usdtForm" class="btn btn-sm btn-success">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
<script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>


@endsection

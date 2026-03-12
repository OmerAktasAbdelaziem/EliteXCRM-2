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
                    <div class="col-xl-12 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Our Assets</h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                        <button type="button" class="btn btn-primary text-white text-center w-auto modal-btn multi-edit-btn mx-1" data-bs-toggle="modal" data-bs-target="#multiEditModal">
                                            <span class="number">0</span>
                                            Selected
                                        </button>
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'assets_create'))
                                            <a href="{{ route('asset.create') }}" class="btn btn-success btn-sm">
                                                Add new asset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <form id="filter_form" method="GET"></form>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle mb-0 table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>
                                                        <input class="form-check-input me-3 check-all-table" data-target="check-asset" type="checkbox">
                                                            Asset Name</th>
                                                        <th>Symbol</th>
                                                        <th>Base Currency</th>
                                                        <th>Category</th>
                                                        <th>Bid Price</th>
                                                        <th>Ask Price</th>
                                                        <th>Active</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>
                                                            <button type="submit" style="display: none" form="filter_form" >Search</button>
                                                            <input type="text" class="form-control" name="filters[name]" value="{{$filters['name']??''}}" placeholder="Name" form="filter_form" />
                                                        </th>
                                                        <th>
                                                            <input type="text" class="form-control" name="filters[symbol]" value="{{$filters['symbol']??''}}" placeholder="Asset Symbol" form="filter_form" />
                                                        </th>
                                                        <th>
                                                            <div class="">
                                                                <div class="input-group">
                                                                    <select class="single-select filter-select form-select" name="filters[currency]" form="filter_form">
                                                                        <option value=""  @if (!isset($filters['currency']) || $filters['currency'] == '') selected @endif>All</option>
                                                                        @foreach ($currencies as $currency)
                                                                            <option value="{{$currency}}" @if (isset($filters['currency']) && $filters['currency'] == $currency) selected @endif>{{$currency}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="">
                                                                <div class="input-group">
                                                                    <select class="single-select filter-select form-select" name="filters[category]" form="filter_form">
                                                                        <option value=""          @if (!isset($filters['category']) || $filters['category'] == '') selected @endif>All</option>
                                                                        <option value="Forex"     @if (isset($filters['category']) && $filters['category'] == 'Forex') selected @endif>Forex</option>
                                                                        <option value="Stocks"    @if (isset($filters['category']) && $filters['category'] == 'Stocks') selected @endif>Stocks</option>
                                                                        <option value="Crypto"    @if (isset($filters['category']) && $filters['category'] == 'Crypto') selected @endif>Crypto</option>
                                                                        <option value="Indx"      @if (isset($filters['category']) && $filters['category'] == 'Indx') selected @endif>Indx</option>
                                                                        <option value="Commodity" @if (isset($filters['category']) && $filters['category'] == 'Commodity') selected @endif>Commodity</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>
                                                            <div class="">
                                                                <div class="input-group">
                                                                    <select class="single-select filter-select form-select" name="filters[is_active]" form="filter_form">
                                                                        <option value=""  @if (!isset($filters['is_active']) || $filters['is_active'] == '') selected @endif>All</option>
                                                                        <option value="1" @if (isset($filters['is_active']) && $filters['is_active'] == 1) selected @endif>Active</option>
                                                                        <option value="0" @if (isset($filters['is_active']) && $filters['is_active'] == 0) selected @endif>Not Active</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <button type="submit" form="filter_form" class="btn btn-sm text-primary bg-transparent p-0"><i class="bx bx-search"></i></button>
                                                        </th>
                                                    </tr>
                                                    @foreach ($assets as $asset)
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input me-3 check-asset check-number" type="checkbox" form="multi_edit_form" name="asset_ids[]" value="{{$asset->id}}" aria-label="...">
                                                                <a href="{{ route('asset.show', $asset->id) }}">
                                                                    {{$asset->name}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$asset->symbol}}
                                                            </td>
                                                            <td>
                                                                {{$asset->currency}}
                                                            </td>
                                                            <td>
                                                                {{$asset->category}}
                                                            </td>
                                                            <td>
                                                                {{$asset->bid_price}}
                                                            </td>
                                                            <td>
                                                                {{$asset->ask_price}}
                                                            </td>
                                                            <td>
                                                                <i class="bx bx-{{$asset->is_active ? 'check text-success' : 'x text-danger'}}" style="font-size: 22px"></i>
                                                            </td>
                                                            <td>{{date('d/m/Y H:i', strtotime($asset->created_at))}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            @include("layouts.table.pagination.footer",['model' => $assets])
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

<div class="modal fade" id="multiEditModal" tabindex="-1" aria-labelledby="multiEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="multiEditModalLabel">Multi Edit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('asset.multiEdit') }}" id="multi_edit_form">
                @csrf
                @method('PUT')
                <div class="row g-2">
                    <div class="col-12">
                        <label for="category" class="form-label">Category</label>
                        <div class="input-group">
                            <select id="category" class="single-select form-select" name="category">
                                <option value="">Select category</option>
                                @foreach (['Forex','Crypto','Stocks','Indx','Commodity'] as $category)
                                    <option value="{{$category}}">{{$category}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="is_active" class="form-label">Active</label>
                        <div class="input-group">
                            <select id="is_active" class="form-select" name="is_active">
                                <option value="">No change</option>
                                    <option value="yes">Active</option>
                                    <option value="no">Not Active</option>
                            </select>
                        </div>
                        @error('is_active')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" form="multi_edit_form" class="btn btn-primary">Submit</button>
        </div>
        </div>
    </div>
</div>

@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection

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
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-shield-quarter me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">
                                    @if ($group->getKey())
                                        Asset Group Edit
                                    @else
                                        Asset Group Registration
                                    @endif
                                </h5>
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $group->getKey()?route('assetGroup.update',$group->getKey()):route('assetGroup.store') }}">
                                @csrf
                                @if ($group->getKey())
                                    @method('PUT')
                                @endif
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$group->name) }}" placeholder="Asset Group Name" required />
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="asset_ids" class="form-label">Assets</label>
                                    <select class="form-select multiple-select" name="asset_ids[]" multiple>
                                        <option value="all">All</option>
                                        <option value="except">Except</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{$asset->id}}" @if (in_array($asset->id, $groupAssets??[]) ) selected @endif>{{$asset->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    @if ($group->getKey())
                                        <button type="submit" class="btn btn-danger px-5">Update</button>
                                    @else
                                        <button type="submit" class="btn btn-danger px-5">Register</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card radius-10 mt-4">
                        <div class="card-body">
                            <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                <div class="row mt-4">
                                    <div class="col-sm-12 col-md-6 align-self-end">
                                    </div>
                                    <div class="col-sm-12 col-md-6 justify-content-end d-flex">
                                        <button type="button" class="btn btn-primary text-white text-center w-auto modal-btn multi-edit-btn mx-1" data-bs-toggle="modal" data-bs-target="#multiEditModal">
                                            <span class="number">0</span>
                                            Selected
                                        </button>
                                        <button type="button" class="btn btn-danger text-center w-auto mx-1" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="bx bx-trash me-2"></i>
                                            Delete
                                        </button>
                                    </div> 
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table align-middle mb-0 table-hover data-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input me-3 check-all-table" data-target="check-asset" type="checkbox">
                                                </th>
                                                <th>
                                                    Asset Name
                                                </th>
                                                <th>Symbol</th>
                                                <th>Base Currency</th>
                                                <th>Type</th>
                                                <th>Leverage</th>
                                                <th>Contract Size</th>
                                                <th>Bid Spread</th>
                                                <th>Ask Spread</th>
                                                <th>Percentage</th>
                                                <th>Active</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assetGroupAssignments as $assetGroupAssignment)
                                           @php
                                       
                                           @endphp
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input me-3 check-asset check-number" type="checkbox" form="multi_edit_form" name="assetGroupAssignment_ids[]" value="{{$assetGroupAssignment->id}}" aria-label="...">
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('asset.show', $assetGroupAssignment->asset) }}">
                                                            {{$assetGroupAssignment->relatedAsset->name}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->relatedAsset->symbol}}
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->relatedAsset->currency}}
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->relatedAsset->category}}
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->leverage??''}}
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->size??''}}
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->bid_spread??''}}
                                                    </td>
                                                    <td>
                                                        {{$assetGroupAssignment->ask_spread??''}}
                                                    </td>
                                                    <td>
                                                        <i class="bx bx-{{($assetGroupAssignment->is_percentage??false) ? 'check text-success' : 'x text-danger'}}" style="font-size: 22px"></i>
                                                    </td>
                                                    <td>
                                                        <i class="bx bx-{{$assetGroupAssignment->relatedAsset->is_active ? 'check text-success' : 'x text-danger'}}" style="font-size: 22px"></i>
                                                    </td>
                                                    <td>{{date('d/m/Y H:i', strtotime($assetGroupAssignment->relatedAsset?->created_at))}}</td>
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

<div class="modal fade" id="multiEditModal" tabindex="-1" aria-labelledby="multiEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="multiEditModalLabel">Multi Edit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('assetGroup.multiEdit',$group->id??0) }}" id="multi_edit_form">
                @csrf
                <div class="row g-2">
                    <div class="col-12">
                        <label for="is_percentage" class="form-label">Percentage</label>
                        <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar-circle'></i></span>
                            <select class="single-select form-select inside-modal" name="is_percentage">
                                <option value="">Select status</option>
                                <option value="Active" @if (old('is_percentage') == 'Active') selected @endif>Percentage</option>
                                <option value="Inactive" @if (old('is_percentage') == 'Inactive') selected @endif>Value</option>
                            </select>
                        </div>
                        @error('is_ftd')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="leverage" class="form-label">Leverage</label>
                        <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar'></i></span>
                            <input type="number" step="any" class="form-control" id="leverage" name="leverage" value="" placeholder="Leverage" />
                        </div>
                        @error('leverage')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="size" class="form-label">Contract Size</label>
                        <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar'></i></span>
                            <input type="number" step="any" class="form-control" id="size" name="size" value="" placeholder="Contract Size" />
                        </div>
                        @error('size')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="bid_spread" class="form-label">Bid Spread</label>
                        <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar'></i></span>
                            <input type="number" step="any" class="form-control" id="bid_spread" name="bid_spread" value="" placeholder="Bid Spread" />
                        </div>
                        @error('bid_spread')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="ask_spread" class="form-label">Ask Spread</label>
                        <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar'></i></span>
                            <input type="number" step="any" class="form-control" id="ask_spread" name="ask_spread" value="" placeholder="Ask Spread" />
                        </div>
                        @error('ask_spread')
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete selected asset from this group?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" form="multi_edit_form" formaction="{{ route('assetGroup.deleteAsset',$group->id??0) }}" href="javascript:;" class="btn btn-danger">Delete</button>
        </div>
        </div>
    </div>
</div>
@endsection
@section("script")
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    <script>
        $(document).ready(function() {
            function updateChoices() {
                $('.select2-selection__rendered').each(function() {
                    var choices = $(this).find('.select2-selection__choice');
                    if (choices.length) {
                        choices.hide();
                        choices.last().show();
                    }
                });
            }
            setInterval(updateChoices, 1);
        });
    </script>
@endsection
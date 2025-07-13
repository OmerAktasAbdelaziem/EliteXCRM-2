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
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$group->name) }}" placeholder="Asset Group Name" required />
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="asset_ids" class="form-label">Assets</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="selected_assets_display" placeholder="Selected assets will appear here..." readonly onclick="toggleAssetSelection()">
                                                <button type="button" class="btn btn-outline-secondary" onclick="toggleAssetSelection()">
                                                    <i class="bx bx-chevron-down" id="dropdown_arrow"></i>
                                                </button>
                                            </div>
                                            <div id="asset_selection_dropdown" class="border rounded mt-2 p-3" style="display: none; max-height: 300px; overflow-y: auto;">
                                                <div class="mb-3">
                                                    <input type="text" class="form-control form-control-sm" id="asset_search" placeholder="Search assets..." onkeyup="filterAssets()">
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="all" id="select_all_assets">
                                                        <label class="form-check-label fw-bold" for="select_all_assets">All Assets</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="except" id="select_except_assets">
                                                        <label class="form-check-label fw-bold" for="select_except_assets">All Except Selected</label>
                                                    </div>
                                                    <hr>
                                                </div>
                                                <div id="assets_list">
                                                    @foreach ($assets as $asset)
                                                        <div class="form-check asset-item" data-asset-name="{{strtolower($asset->name)}}">
                                                            <input class="form-check-input asset-checkbox" type="checkbox" name="asset_ids[]" value="{{$asset->id}}" id="asset_{{$asset->id}}" @if (in_array($asset->id, $group->asset_ids??[]) ) checked @endif>
                                                            <label class="form-check-label" for="asset_{{$asset->id}}">{{$asset->name}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="selected-assets-preview">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">Selected Assets Preview:</small>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAssetSelection()">
                                                        <i class="bx bx-plus"></i> Add Asset
                                                    </button>
                                                </div>
                                                <div id="selected_assets_list" class="border rounded p-2" style="min-height: 100px; max-height: 200px; overflow-y: auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                            @foreach ($groupAssets as $asset)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input me-3 check-asset check-number" type="checkbox" form="multi_edit_form" name="asset_ids[]" value="{{$asset->id}}" aria-label="...">
                                                    </td>
                                                    <td>
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
                                                        {{$asset->leverage[$group->id]??''}}
                                                    </td>
                                                    <td>
                                                        {{$asset->size[$group->id]??''}}
                                                    </td>
                                                    <td>
                                                        {{$asset->bid_spread[$group->id]??''}}
                                                    </td>
                                                    <td>
                                                        {{$asset->ask_spread[$group->id]??''}}
                                                    </td>
                                                    <td>
                                                        <i class="bx bx-{{($asset->is_percentage[$group->id]??false) ? 'check text-success' : 'x text-danger'}}" style="font-size: 22px"></i>
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

            updateSelectedAssetsDisplay();
            updateSelectedAssetsList();

            $('.asset-checkbox').on('change', function() {
                updateSelectedAssetsDisplay();
                updateSelectedAssetsList();
                handleSpecialSelections();
            });

            $('#select_all_assets').on('change', function() {
                if (this.checked) {
                    $('#select_except_assets').prop('checked', false);
                    $('.asset-checkbox').prop('checked', true);
                } else {
                    $('.asset-checkbox').prop('checked', false);
                }
                updateSelectedAssetsDisplay();
                updateSelectedAssetsList();
            });

            $('#select_except_assets').on('change', function() {
                if (this.checked) {
                    $('#select_all_assets').prop('checked', false);
                }
                updateSelectedAssetsDisplay();
                updateSelectedAssetsList();
            });
        });

        function toggleAssetSelection() {
            var dropdown = $('#asset_selection_dropdown');
            var arrow = $('#dropdown_arrow');
            
            if (dropdown.is(':visible')) {
                dropdown.hide();
                arrow.removeClass('bx-chevron-up').addClass('bx-chevron-down');
            } else {
                dropdown.show();
                arrow.removeClass('bx-chevron-down').addClass('bx-chevron-up');
            }
        }

        function removeAssetFromSelection(assetId) {
            $('#asset_' + assetId).prop('checked', false);
            updateSelectedAssetsDisplay();
            updateSelectedAssetsList();
            handleSpecialSelections();
        }

        function removeSpecialSelection(type) {
            if (type === 'all') {
                $('#select_all_assets').prop('checked', false);
                $('.asset-checkbox').prop('checked', false);
            } else if (type === 'except') {
                $('#select_except_assets').prop('checked', false);
            }
            updateSelectedAssetsDisplay();
            updateSelectedAssetsList();
        }

        function addAssetToSelection(assetId) {
            $('#asset_' + assetId).prop('checked', true);
            updateSelectedAssetsDisplay();
            updateSelectedAssetsList();
            handleSpecialSelections();
        }

        function filterAssets() {
            var searchText = $('#asset_search').val().toLowerCase();
            $('.asset-item').each(function() {
                var assetName = $(this).data('asset-name');
                if (assetName.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function updateSelectedAssetsDisplay() {
            var selectedAssets = [];
            
            if ($('#select_all_assets').is(':checked')) {
                selectedAssets.push('All Assets');
            } else if ($('#select_except_assets').is(':checked')) {
                selectedAssets.push('All Except Selected');
            } else {
                $('.asset-checkbox:checked').each(function() {
                    var assetName = $('label[for="' + this.id + '"]').text();
                    selectedAssets.push(assetName);
                });
            }

            var displayText = selectedAssets.length > 0 ? selectedAssets.join(', ') : 'No assets selected';
            if (displayText.length > 50) {
                displayText = selectedAssets.length + ' assets selected';
            }
            
            $('#selected_assets_display').val(displayText);
        }

        function updateSelectedAssetsList() {
            var selectedAssetsList = $('#selected_assets_list');
            selectedAssetsList.empty();

            if ($('#select_all_assets').is(':checked')) {
                selectedAssetsList.append('<span class="badge bg-primary me-1 mb-1">All Assets <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6em;" onclick="removeSpecialSelection(\'all\')"></button></span>');
            } else if ($('#select_except_assets').is(':checked')) {
                selectedAssetsList.append('<span class="badge bg-warning me-1 mb-1">All Except Selected <button type="button" class="btn-close ms-1" style="font-size: 0.6em;" onclick="removeSpecialSelection(\'except\')"></button></span>');
                $('.asset-checkbox:checked').each(function() {
                    var assetName = $('label[for="' + this.id + '"]').text();
                    var assetId = this.value;
                    selectedAssetsList.append('<span class="badge bg-secondary me-1 mb-1">' + assetName + ' (Excluded) <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6em;" onclick="removeAssetFromSelection(\'' + assetId + '\')"></button></span>');
                });
            } else {
                $('.asset-checkbox:checked').each(function() {
                    var assetName = $('label[for="' + this.id + '"]').text();
                    var assetId = this.value;
                    selectedAssetsList.append('<span class="badge bg-success me-1 mb-1">' + assetName + ' <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6em;" onclick="removeAssetFromSelection(\'' + assetId + '\')"></button></span>');
                });
            }

            if (selectedAssetsList.children().length === 0) {
                selectedAssetsList.append('<span class="text-muted">No assets selected</span>');
            }
        }

        function handleSpecialSelections() {
            if ($('#select_all_assets').is(':checked') && $('.asset-checkbox:not(:checked)').length > 0) {
                $('#select_all_assets').prop('checked', false);
            }
            
            if ($('.asset-checkbox:checked').length === $('.asset-checkbox').length && $('.asset-checkbox').length > 0) {
                $('#select_all_assets').prop('checked', true);
                $('#select_except_assets').prop('checked', false);
            }
        }

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#asset_selection_dropdown, #selected_assets_display, button[onclick="toggleAssetSelection()"]').length) {
                $('#asset_selection_dropdown').hide();
                $('#dropdown_arrow').removeClass('bx-chevron-up').addClass('bx-chevron-down');
            }
        });

        $('#asset_selection_dropdown').on('click', function(event) {
            event.stopPropagation();
        });
    </script>
@endsection
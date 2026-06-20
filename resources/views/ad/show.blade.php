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
                                        @if ($ad->getKey())
                                            Ad Edit
                                        @else
                                            Ad Registration
                                        @endif
                                    </h5>                                    
                                </div>
                                @if ($ad->getKey() && ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'ads_delete')))
                                    <button type="button" class="btn btn-sm btn-danger"data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                @endif
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $ad->getKey()?route('ads.update',$ad->getKey()):route('ads.store') }}">
                                @csrf
                                @if ($ad->getKey())
                                    @method('PUT')
                                @endif

                                <div class="col-md-12">
                                    <label for="sheet_name" class="form-label">Sheet Name</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" id="sheet_name" name="sheet_name" value="{{ old('sheet_name', $ad->sheet_name) }}" placeholder="Sheet name" required />
                                    </div>
                                    @error('sheet_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="sheet_url" class="form-label">Sheet url</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" id="sheet_url" name="sheet_url" value="{{ old('sheet_url', $ad->sheet_url) }}" placeholder="Sheet url" required />
                                    </div>
                                    @error('sheet_url')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                             
                                <div class="col-12">
                                    <label class="form-label">Sheet country</label>
                                    <div class="input-group">
                                        <select class="single-select form-select" name="sheet_country">
                                            @foreach(config('countries') as $code => $name)
                                                <option value="{{ $code }}" {{ old('sheet_country', $ad->sheet_country) == $code ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('sheet_country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($ad->getKey())
                                    @foreach ($headers as $index => $header)
                                        <div class="col-md-6">
                                            {{$header}}
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select single-select" name="fields[{{$header}}]">
                                                <option value="">Do Not Insert</option>
                                                @foreach ($fields as $value => $fieldName)
                                                    @if($ad->fields->isNotEmpty())                            
                                                        <option value="{{$value}}" @if($ad->fields->contains(function($field) use ($header, $value) {
                                                            return $field->sheet_field === $header && $field->crm_field == $value;
                                                        })) selected @endif>
                                                            {{$fieldName}}
                                                        </option>
                                                    @else
                                                        <option value="{{$value}}" @if ($header == $value || strtolower($header) == strtolower($fieldName) || (isset($defaultHeaders[$value]) && $header == $defaultHeaders[$value])) selected @endif>{{$fieldName}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <hr class="my-2 @if ($index % 2 == 0) bg-danger @endif"> --}}
                                    @endforeach
                                @endif

                                <div class="col-12">
                                    @if ($ad->getKey())
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'ads_edit'))
                                            <button type="submit" class="btn btn-danger px-5">Update</button>
                                        @endif
                                    @else
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'ads_create'))
                                            <button type="submit" class="btn btn-danger px-5">Register</button>
                                        @endif
                                    @endif
                                </div>
                            </form>
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
            Are you sure you want to delete selected asset from this Ad?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form method="post" action="{{ route('ads.delete', $ad->id ?? 0) }}">
                @csrf
                <input type="hidden" name="_method" value="delete">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
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



        
        
        // function filterAssets() {
        //     var searchText = $('#asset_search').val().toLowerCase();
        //     $('.asset-item').each(function() {
        //         var assetName = $(this).data('asset-name');
        //         if (assetName.includes(searchText)) {
        //             $(this).show();
        //         } else {
        //             $(this).hide();
        //         }
        //     });
        // }

        

        
    </script>
@endsection
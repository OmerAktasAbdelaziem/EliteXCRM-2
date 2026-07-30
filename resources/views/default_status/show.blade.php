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
                                        @if ($defaultStatus->getKey())
                                            Default Status Edit
                                        @else
                                            Default Status Registration
                                        @endif
                                    </h5>                                    
                                </div>
                                {{-- @if ($defaultStatus->getKey() && ($isSuperAdmin))
                                    <button type="button" class="btn btn-sm btn-danger"data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                @endif --}}
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $defaultStatus->getKey()?route('default-status.update',$defaultStatus->getKey()):route('default-status.store') }}">
                                @csrf
                                @if ($defaultStatus->getKey())
                                    @method('PUT')
                                @endif

                                <div class="col-md-12">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                        <input  class="form-control" id="name" name="name" type="text" required value="{{ old('name', $defaultStatus->name) }}">
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    @if ($defaultStatus->getKey())
                                        @if ($isSuperAdmin)
                                            <button type="submit" class="btn btn-danger px-5">Update</button>
                                        @endif
                                    @else
                                        @if ($isSuperAdmin)
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
            Are you sure you want to delete selected asset from this Default Status?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form method="post" action="{{ route('default-status.delete', $defaultStatus->id ?? 0) }}">
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
@endsection
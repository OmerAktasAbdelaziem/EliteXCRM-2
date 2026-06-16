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
                                        @if ($question->getKey())
                                            Question Edit
                                        @else
                                            Question Registration
                                        @endif
                                    </h5>                                    
                                </div>
                                @if ($question->getKey() && ($isSuperAdmin || UserPermission::hasPermission($userAuth, 'question_delete')))
                                    <button type="button" class="btn btn-sm btn-danger"data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                @endif
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $question->getKey()?route('question.update',$question->getKey()):route('question.store') }}">
                                @csrf
                                @if ($question->getKey())
                                    @method('PUT')
                                @endif

                                <div class="col-md-12">
                                    <label for="question_text" class="form-label">Question Text</label>
                                    <div class="input-group">
                                        <textarea class="form-control" id="question_text" name="question_text" rows="3" placeholder="Enter question text here..." required>{{ old('question_text', $question->question_text) }}</textarea>
                                    </div>
                                    @error('question_text')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="is_text" class="form-label">Question Type</label>
                                    <select class="form-select select2-single" id="is_text" name="is_text" required>
                                        <option value="1" {{ old('is_text', $question->is_text) == '1' || old('is_text', $question->is_text) === true ? 'selected' : '' }}>Text Input</option>
                                        <option value="0" {{ old('is_text', $question->is_text) == '0' || old('is_text', $question->is_text) === false ? 'selected' : '' }}>True / False</option>
                                    </select>
                                    @error('is_text')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    @if ($question->getKey())
                                        @if ($isSuperAdmin || UserPermission::hasPermission($userAuth, 'question_edit'))
                                            <button type="submit" class="btn btn-danger px-5">Update</button>
                                        @endif
                                    @else
                                        @if ($isSuperAdmin || UserPermission::hasPermission($userAuth, 'question_create'))
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
            Are you sure you want to delete selected asset from this Question?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form method="post" action="{{ route('question.delete', $question->id ?? 0) }}">
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
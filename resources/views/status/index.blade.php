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
                                        <h5 class="mb-1">Our Statuses</h5>
                                    </div>
                                    <div class="font-22 ms-auto">
                                        @if (isset($options['status_create']))
                                            <a href="{{ route('status.create') }}" class="btn btn-success btn-sm">
                                                Add new status
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
                                                        <th>Status Name</th>
                                                        <th>parts</th>
                                                        <th>Created At</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($statuses as $status)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('status.show', $status->id) }}">
                                                                    {{$status->name}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @foreach ($parts as $part)
                                                                    @if(in_array($part->id, $status->part_ids)) 
                                                                        {{ $part->name }} ||
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td>{{date('d/m/Y H:i', strtotime($status->created_at))}}</td>
                                                            <td class="text-end">
                                                                @if (isset($options['status_delete']))
                                                                    <button type="button" formaction="{{ route('status.delete', $status->id) }}" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent"  data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                        <i class="bx bx-trash"></i>
                                                                    </button>
                                                                @endif
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
                    Are you sure you want to delete selected items?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form class="" method="POST" id="deleteForm">
                        @csrf
                        @method('delete')
                    </form>
                    <button type="submit" form="deleteForm" href="javascript:;" class="btn btn-danger">Delete</button>
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

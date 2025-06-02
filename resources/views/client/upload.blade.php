@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-9 mx-auto mt-2">
                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bxs-user me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">Lead Upload</h5>
                            </div>
                            <hr>
                            <form class="row g-1 align-items-center " method="POST" action="{{ route('client.excel.upload') }}" name="addform" id="addform">
                                @csrf
                                <input type="hidden" name="path" value="{{$path}}">
                                @foreach ($headers as $index => $header)
                                    <div class="col-md-6">
                                        {{$header}}
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select single-select" name="header[{{$header}}]">
                                            <option value="">Do Not Insert</option>
                                            @foreach ($fields as $value => $fieldName)
                                                <option value="{{$value}}" @if ($header == $value || strtolower($header) == strtolower($fieldName)) selected @endif>{{$fieldName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <hr class="my-2 @if ($index % 2 == 0) bg-danger @endif">
                                @endforeach
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger px-5">Register</button>
                                </div>
                            </form>
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
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection



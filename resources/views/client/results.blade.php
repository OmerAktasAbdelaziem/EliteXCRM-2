@extends("layouts.app")
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
                                <h5 class="mb-0 text-danger">Assign File</h5>
                            </div>
                            <hr>
                            <div class="table-responsive mt-2">
                                <table class="table align-middle mb-0 table-hover opened-order table-striped table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Status</th>
                                            <th>Message</th>
                                            <th>Leads</th>
                                            <th>IDs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="badge bg-success">Success</span></td>
                                            <td>Success</td>
                                            <td>{{count($success)}}</td>
                                            <td>{{implode(',', $success)}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-danger">Failed</span></td>
                                            <td>Dublicate</td>
                                            <td>{{count($repeated)}}</td>
                                            <td>{{implode(',', $repeated)}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-danger">Failed</span></td>
                                            <td>Empty FirstName</td>
                                            <td>{{count($emptyFirstName)}}</td>
                                            <td>{{implode(',', $emptyFirstName)}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-danger">Failed</span></td>
                                            <td>Empty Country</td>
                                            <td>{{count($emptyCountry)}}</td>
                                            <td>{{implode(',', $emptyCountry)}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-danger">Failed</span></td>
                                            <td>Empty Phone1</td>
                                            <td>{{count($emptyPhone1)}}</td>
                                            <td>{{implode(',', $emptyPhone1)}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-danger">Failed</span></td>
                                            <td>Empty Email</td>
                                            <td>{{count($emptyEmail)}}</td>
                                            <td>{{implode(',', $emptyEmail)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



<form id="filter_form2">
    <input type="hidden" name="tab" value="{{$tab}}">
    <div class="row mt-4">
        <div class="col-sm-12 col-md-4 align-self-end">
            <label class="d-flex align-items-center">
                Show &nbsp;
                <select name="limit" class="form-select form-select-sm entries-per-page" style="width: 70px" data-tab="{{$tab}}">
                    @if ($isSuperAdmin || $isPipelineAdmin || Auth::user()->ledParts->count() > 0 || Auth::user()->ledTeams->count() > 0 || !Auth::user()->team)
                        @php
                            $pages = [15,25,50,100]
                        @endphp
                    @else
                        @php
                            $pages = [6,10,20]
                        @endphp
                    @endif
                    @foreach($pages as $perPage)
                        <option value="{{ $perPage }}" @if ($model->perPage() == $perPage) selected @endif>{{ $perPage }}</option>
                    @endforeach
                </select>
                &nbsp;
                entries
            </label>
            
        </div>
        <div class="col-sm-12 col-md-8 justify-content-end d-flex">
            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'import_clients'))
                <div class="ms-auto mx-1">
                    <input class="form-control" type="file" form="excel-{{$check_type}}" name="excel_file" accept=".xls, .xlsx, .csv" onchange="$('#excel-{{$check_type}}').submit();">
                    @error('excel_file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endif
            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'export_clients'))
                <button type="button" class="btn btn-success text-white text-center w-auto mx-1" data-bs-toggle="modal" data-bs-target="#exportModal-{{$check_type}}">
                    <i class="bx bx-download me-2"></i>
                    Export Clients
                </button>
            @endif
            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_edit'))
                <button type="button" class="btn btn-primary text-white text-center w-auto modal-btn multi-edit-btn mx-1" data-bs-toggle="modal" data-bs-target="#multiEdit-{{$check_type}}">
                    <span class="number">0</span>
                    Selected
                </button>
            @endif
            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_delete'))
                <button type="button" class="btn btn-danger text-center w-auto mx-1" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$check_type}}">
                    <i class="bx bx-trash me-2"></i>
                    Delete
                </button>
            @endif

            <button type="submit" class="btn btn-danger text-white text-center w-auto modal-btn multi-edit-btn mx-1" form="addemployee" formmethod="POST" formaction="{{ route('client.clearfilters') }}" href="javascript:;">
                Clear Filters
            </button>
        </div> 
    </div>
</form>

@if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_create'))
    <form action="{{ route('client.excel.check') }}" method="POST" enctype="multipart/form-data" id="excel-{{$check_type}}">
        @csrf
    </form>
@endif
@if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_edit'))
<?php /* @if (isset($options['leads_can_update']) ||isset($options['smart_can_update']) ) */ ?>
    <div class="modal fade" id="multiEdit-{{$check_type}}" tabindex="-1" aria-labelledby="multiEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multiEditLabel">Bulk Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('client.multiEdit') }}" id="multi_edit-{{$check_type}}">
                    @csrf
                    <input type="hidden" class="client_id" name="client_id">
                    <div class="row">
                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_show'))
                            <div class="col-12">
                                <label class="form-label">Country</label>
                                <div class="input-group">
                                    <button class="btn" style="border:1px solid #ced4da;border-right:none" type="button"><i class='bx bx-world'></i></button>
                                    <select class="single-select form-select inside-modal" name="country">
                                        <option value="" selected>Select Country</option>
                                        @foreach(config('countries') as $code => $name)
                                            <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_date_show'))
                            <div class="col-12 mt-2">
                                <label for="is_ftd" class="form-label">FTD</label>
                                <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar-circle' ></i></span>
                                    <select class="single-select form-select inside-modal" name="is_ftd">
                                        <option value="">Select status</option>
                                        <option value="Active" @if (old('is_ftd') == 'Active') selected @endif>Active</option>
                                        <option value="Inactive" @if (old('is_ftd') == 'Inactive') selected @endif>Inactive</option>
                                    </select>
                                </div>
                                @error('is_ftd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <?php /* @if (isset($options['smart_amount']))*/ ?>
                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'smart_amount'))
                            <div class="col-12 mt-2">
                                <label for="ftd_amount" class="form-label">FTD Amount</label>
                                <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bx-dollar' ></i></span>
                                    <input type="number" class="form-control" id="ftd_amount" name="ftd_amount" value="{{ old('ftd_amount') }}" placeholder="Amount" />
                                </div>
                                @error('ftd_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_show'))
                        
                            <div class="col-12 mt-2">
                                <label class="form-label">Assigned User</label>
                                <div class="input-group">
                                    <button class="btn" style="border:1px solid #ced4da;border-right:none" type="button"><i class='bx bx-support'></i></button>
                                    <select class="single-select form-select inside-modal" name="user_id">
                                        <option value="" >Select User</option>
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'show_unassigned_leads'))
                                            <option value="no" >No User</option>
                                        @endif
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}" @if (old('user_id') == $user->id) selected @endif>{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_show'))
                        
                            <div class="col-12 mt-2">
                                <label class="form-label">Sales Status</label>
                                <div class="input-group">
                                    <button class="btn" style="border:1px solid #ced4da;border-right:none" type="button"><i class='bx bx-support'></i></button>
                                    <select class="single-select form-select inside-modal" name="sales_status">
                                        <option value="" >Select Status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{$status->name}}" @if (old('sales_status') == $status->name) selected @endif>{{$status->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sales_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="multi_edit-{{$check_type}}" class="btn btn-primary">Submit</button>
            </div>
            </div>
        </div>
    </div>
@endif
@if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_delete'))
    <div class="modal fade" id="deleteModal-{{$check_type}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete selected leads?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addemployee" formmethod="POST" formaction="{{ route('client.delete') }}" href="javascript:;" class="btn btn-danger">Delete</button>
            </div>
            </div>
        </div>
    </div>
@endif

@if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'export_clients'))
    <div class="modal fade" id="exportModal-{{$check_type}}" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('client.export') }}" id="export_form-{{$check_type}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Assigned User</label>
                                <select class="form-select" name="assigned_user">
                                    <option value="">All Users</option>
                                    @if(isset($users))
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <select class="form-select" name="country">
                                    <option value="">All Countries</option>
                                        @if(isset($countries))
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}">{{ config("countries.$country") }}</option>
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Account Type</label>
                                <select class="form-select" name="account_type">
                                    <option value="">All Account Types</option>
                                    <option value="demo">Demo</option>
                                    <option value="real">Real</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Include Status</label>
                                <select class="form-select" name="include_status[]" multiple>
                                    @if(isset($statuses))
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="form-label">Exclude Status</label>
                                <select class="form-select" name="exclude_status[]" multiple>
                                    @if(isset($statuses))
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="export_form-{{$check_type}}" class="btn btn-success">
                        <i class="bx bx-download me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css?v2.944') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v2.944') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v2.944') }}">
    <style>
        .dcalendarpicker .dudp__wrapper {
            top: 24px !important;
            bottom: unset !important;
        }
        .page-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            display: none !important;
        }
        .breadcrumb-item{
            border-left: 1.5px solid rgb(255 255 255 / 18%);
        }
        .input-group-text {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card {
            margin-bottom:0 !important; 
        }
        #DataTables_Table_0_length,#DataTables_Table_0_paginate,#DataTables_Table_1_length,#DataTables_Table_1_paginate{
            display: none;
        }
        .text-green{
            color: #10ff00;
        }
        .text-red{
            color: #af0000;
        }
    </style>
@endsection
@section('title',
    $client->first_name .
    ' ' .
    $client->last_name
)
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="@if($client->broker_id && !isset($client->options['isEnabled']))background-color: #f14f5e;@else background-color: #0d6efd; @endif margin-bottom:0;border-radius: 0;box-shadow: none !important">
                        <div class="card-body" style="padding-bottom: 5px">
                            <div class="row text-white">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <small class="form-label">ID</small>
                                            <small>
                                                <a class="text-white" href="{{ route('client.show', $client->id) }}" target="_blank" rel="noopener noreferrer">{{$client->id}}</a>
                                            </small>
                                        </div>
                                        <div class="col">
                                            <small class="form-label">TP</small>
                                            <small>
                                                @if ($client->broker_id)
                                                    <a class="text-white" href="{{ route('main_tp.show', $client->id) }}">{{$client->broker_id}}</a>
                                                @else
                                                    <div class="text-white">
                                                        -
                                                    </div>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <small class="form-label">TP Name</small>
                                    <h3 class="text-white mb-3">
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show'))
                                            {{$client->first_name.' '}}
                                        @endif
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show'))
                                            {{$client->last_name}}
                                        @endif
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Balance</small>
                                    <h3 class="mb-3 balance {{ $finance['balance'] < 0 ? 'text-red' : 'text-green' }}">
                                        $ {{number_format((floor($finance['balance'] * 100) / 100), 2, '.', ',');}} 
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">PnL</small>
                                    <h3 class="mb-3 currentPL {{ $finance['currentPL'] < 0 ? 'text-red' : 'text-green' }}">
                                        $ {{ number_format($finance['currentPL'], 3, '.', ',') }}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Total Deposits</small>
                                    <h3 class="text-white mb-3 totalDeposit">
                                        $ {{number_format($finance['totalDeposit'], 2, '.', ',');}}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Credit</small>
                                    <h3 class="text-white mb-3 credit">
                                        $ {{number_format($finance['credit'], 2, '.', ',');}}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Bonus</small>
                                    <h3 class="text-white mb-3 bonus">
                                        $ {{number_format($finance['bonus'], 2, '.', ',');}}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Equity</small>
                                    <h3 class="mb-3 equity {{ ($finance['equity']) < 0 ? 'text-red' : 'text-green' }}">
                                        $ {{ number_format((floor($finance['equity'] * 100) / 100), 2, '.', ',') }}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Used Margin</small>
                                    <h3 class="mb-3 usedMargin {{ $finance['usedMargin'] < 0 ? 'text-red' : 'text-green' }}">
                                        $ {{number_format((floor($finance['usedMargin'] * 100) / 100), 2, '.', ',');}}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Free Margin</small>
                                    <h3 class="mb-3 freeMargin {{ $finance['freeMargin'] < 0 ? 'text-red' : 'text-green' }}">
                                        $ {{number_format($finance['freeMargin'], 2, '.', ',');}}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Total Withdrawals</small>
                                    <h3 class="mb-3 text-white totalWithdrawal">
                                        $ {{number_format($finance['totalWithdrawal'], 2, '.', ',');}}
                                    </h3>
                                </div>
                                <div class="col">
                                    <small class="form-label">Net Deposits</small>
                                    <h3 class="mb-3 text-white totalDeposit">
                                        $ {{number_format($finance['totalDeposit']-$finance['totalWithdrawal'], 2, '.', ',');}}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <div class="@if ($tab == 'history') col-lg-12 @endif col-lg-7 history">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'info') active @endif tab" data-bs-toggle="tab" href="#show" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-show font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Information</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'opened') active @endif tab" data-bs-toggle="tab" href="#opened" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-trending-up font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Opened Order</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'closed') active @endif tab" data-bs-toggle="tab" href="#closed" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-trending-down font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Closed Order</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'trx') active @endif tab" data-bs-toggle="tab" href="#trx" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-dollar font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Money Transaction</div>
                                                </div>
                                            </a>
                                        </li>
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions'))
                                        
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link @if ($tab == 'actions') active @endif tab" data-bs-toggle="tab" href="#actions" id="view-tab" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class="bx bx-history font-18 me-1"></i>
                                                        </div>
                                                        <div class="tab-title">Actions</div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link history_tab @if ($tab == 'history') active @endif" data-bs-toggle="tab" href="#history" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-dollar-circle font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Money History</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'kyc') active @endif tab" data-bs-toggle="tab" href="#kyc" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-user-circle font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">KYC</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-3">
                                        <div class="tab-pane fade @if ($tab == 'info') active show @endif" id="show" role="tabpanel">
                                            <div class="row">
                                                @if (!isset($client->options['enableWithdrawalRequest']) || !isset($client->options['enableDepositRequest']) || !isset($client->options['isEnabled']))
                                                    <div class="alert alert-danger">
                                                        Missing Default Options!!
                                                    </div>
                                                @endif
                                            </div>
                                            <form class="row g-3 ajax-form" action="{{ route('mainTp.update',$client->id) }}" method="POST" name="addform" id="addform" data-tab="info">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-12 text-end">
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_yes_no'))
                                                    
                                                        <button type="button" class="btn p-0" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#optionsModal"><i class="text-secondary bx bx-cog h5 mb-0"></i></button>
                                                    @endif
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_can_update'))
                                                    
                                                        <button type="button" id="edit_btn" class="btn p-0" style="background-color: transparent"><i class="text-primary bx bx-edit h5 mb-0"></i></button>
                                                        <a href="{{ route('main_tp.show', $client->id) }}" type="button" id="cancel_btn" class="btn p-0 d-none" style="background-color: transparent"><i class="text-secondary bx bx-x h5 mb-0"></i></a>
                                                        <button type="submit" id="save_btn" class="btn p-0 d-none" style="background-color: transparent"><i class="text-success bx bx-check h5 mb-0"></i></button>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_edit')) name="first_name" @endif readonly value="{{ old('first_name',$client->first_name) }}" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_edit')) editable @endif" id="first_name" placeholder="First Name"  />
                                                        </div>
                                                        @error('first_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif ( UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_hide'))
                                                        <div>
                                                            {{ mb_substr($client->first_name, 0, ceil(mb_strlen($client->first_name) / 2)) }} ******
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                   @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show'))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_edit')) editable @endif" id="last_name" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_edit')) name="last_name" @endif readonly value="{{ old('last_name',$client->last_name) }}" placeholder="Last Name"/>
                                                        </div>
                                                        @error('last_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_hide'))
                                                        <div>
                                                            {{ mb_substr($client->last_name, 0, ceil(mb_strlen($client->last_name) / 2)) }} ******
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email Address</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_show'))
                                                        <div class="input-group">
                                                            <input type="mail" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_edit')) editable @endif" id="email" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_edit')) name="email" @endif value="{{ old('email',$client->email) }}" readonly placeholder="Email Address" />
                                                        </div>
                                                        @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_email_hide'))
                                                        <div>
                                                            ********{{ substr($client->email, 8) }}
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone1" class="form-label">Primary Number</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="tel" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_edit')) editable @endif" id="phone1" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_edit')) name="phone1" @endif readonly value="{{ old('phone1',$client->phone1) }}" placeholder="Primary Number"/>
                                                        </div>
                                                        @error('phone1')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_hide'))
                                                        <div>
                                                            {{ substr($client->phone1, 0, ceil(strlen($client->phone1) / 2)) }}******
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone2" class="form-label">Secondary Number</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_show'))
                                                        <div class="input-group">
                                                            <input type="tel" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_edit')) editable @endif" id="phone2" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_edit')) name="phone2" @endif readonly value="{{ $client->phone2 }}" placeholder="Secondary Number"/>
                                                        </div>
                                                        @error('phone2')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_hide'))
                                                        <div>
                                                            {{ substr($client->phone2, 0, ceil(strlen($client->phone2) / 2)) }}******
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sales_status" class="form-label text-warning">Sales Status</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_show'))
                                                        <div class="input-group">
                                                            <select id="sales_status" class="single-select form-select @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit')) editable @endif" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit')) name="sales_status" @endif disabled>
                                                                <option value="">Select Status</option>
                                                                @foreach ($statuses as $status)
                                                                    <option value="{{$status->name}}" @if (old('sales_status',$client->sales_status) == $status->name) selected @endif>{{$status->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('sales_status')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="is_ftd" class="form-label">First Deposit Status</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_status_show'))
                                                        <div class="form-check form-switch p-0 pt-2" style="display: flex; flex-wrap: wrap;">
                                                            <label class="form-check-label" for="is_ftd" style="order: 1; margin-right: 45px;">Inactive</label>
                                                            <input class="form-check-input is_ftd" disabled value="1" type="checkbox" id="is_ftd" style="order: 2; margin-right: 10px;" @if ($client->is_ftd) checked @endif>
                                                            <label class="form-check-label" for="is_ftd" style="order: 3; margin-right: 10px;">Active</label>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="enabled" class="form-label">Enabled</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_leads_enabled_show'))
                                                    
                                                        <div class="form-check form-switch p-0 pt-2" style="display: flex; flex-wrap: wrap;">
                                                            <label class="form-check-label" for="enabled" style="order: 1; margin-right: 45px;">Inactive</label>
                                                            <input class="form-check-input is_ftd" disabled value="1" type="checkbox" id="enabled" style="order: 2; margin-right: 10px;" @if ($client->account_type == 'Real') checked @endif>
                                                            <label class="form-check-label" for="enabled" style="order: 3; margin-right: 10px;">Active</label>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="user_id" class="form-label">Assigned User</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_show'))
                                                    
                                                        <div class="input-group">
                                                            <select id="user_id" class="single-select form-select @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_edit')) editable @endif" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_edit')) name="user_id" @endif disabled>
                                                                <option value="">Select User</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{$user->id}}" @if (old('user_id',$client->user_id) == $user->id) selected @endif>{{$user->username}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('user_id')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="username" class="form-label">Username</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_username_show'))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_username_edit')) editable @endif" id="username" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_username_edit')) name="username" @endif value="{{ old('username',$client->username) }}" readonly placeholder="Username" />
                                                        </div>
                                                        @error('username')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">Password</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_password_show'))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control password @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_password_edit')) editable @endif" id="password" readonly @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_password_edit')) name="password" @endif value="{{ old('password',$client->password_text) }}"/>
                                                            <button class="btn d-none generate-password" style="border:1px solid #ced4da;" title="Generate Password" type="button"><i class='bx bx-reset'></i></button>
                                                        </div>
                                                        @error('password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <label for="usdt" class="form-label">USDT Address (Leave Blank to use default)</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_usdt_address_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_usdt_address_edit')) editable @endif" readonly id="usdt" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_usdt_address_edit')) name="usdt" @endif value="{{ old('usdt',$client->usdt) }}" placeholder="USDT Address" />
                                                        </div>
                                                        @if (!$client->usdt)
                                                            <div class="text-red">
                                                                Default : 
                                                                @if ($client->source == 'BNC')
                                                                    {{auth()->user()->pipeline->usdt['BNC']??''}}
                                                                @else
                                                                    {{auth()->user()->pipeline->usdt['phoenix']??''}}
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ftd_amount" class="form-label">FTD Amount</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_amount_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" readonly id="ftd_amount" value="{{ number_format($finance['ftd_amount'], 2, '.', ',') }}" placeholder="Amount" />
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="leverage" class="form-label">Leverage</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_leads_leverage_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_leads_leverage_edit')) editable @endif" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_leads_leverage_edit')) name="leverage" @endif readonly id="leverage" value="{{$client->leverage }}" placeholder="Leverage" />
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="account_type" class="form-label">Account type</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_account_type_show'))
                                                        <div class="input-group">
                                                            <select id="account_type" class="single-select form-select editable" name="account_type" disabled>
                                                                <option value="" @if (!$client->broker_id) selected @endif></option>
                                                                <option value="Real" @if ($client->account_type == 'Real') selected @endif>Real</option>
                                                                <option value="Demo" @if ($client->account_type == 'Demo') selected @endif>Demo</option>
                                                            </select>
                                                        </div>
                                                        @error('account_type')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="asset_group_id" class="form-label">Asset Group</label>
                                                    @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_asset_group_show'))
                                                        <div class="input-group">
                                                            <select id="asset_group_id" class="single-select form-select @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_asset_group_edit')) editable @endif" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_asset_group_edit')) name="asset_group_id" @endif disabled>
                                                                <option value="">Select Group</option>
                                                                @foreach ($asset_groups as $asset_group)
                                                                    <option value="{{$asset_group->id}}" @if ($asset_group->id == $client->asset_group_id) selected @endif>{{$asset_group->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('asset_group_id')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="country" class="form-label">Country</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_show'))
                                                        <div class="input-group">
                                                            <select id="country" class="single-select form-select @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_edit')) editable @endif" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_edit')) name="country" @endif disabled>
                                                                <option value="{{old('country',$client->country)}}" selected>{{old('country',$client->country)}}</option>
                                                                <option value="Afghanistan">Afghanistan</option>
                                                                <option value="Åland Islands">Åland Islands</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antarctica">Antarctica</option>
                                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                                <option value="Argentina">Argentina</option>
                                                                <option value="Armenia">Armenia</option>
                                                                <option value="Aruba">Aruba</option>
                                                                <option value="Australia">Australia</option>
                                                                <option value="Austria">Austria</option>
                                                                <option value="Azerbaijan">Azerbaijan</option>
                                                                <option value="Bahamas">Bahamas</option>
                                                                <option value="Bahrain">Bahrain</option>
                                                                <option value="Bangladesh">Bangladesh</option>
                                                                <option value="Barbados">Barbados</option>
                                                                <option value="Belarus">Belarus</option>
                                                                <option value="Belgium">Belgium</option>
                                                                <option value="Belize">Belize</option>
                                                                <option value="Benin">Benin</option>
                                                                <option value="Bermuda">Bermuda</option>
                                                                <option value="Bhutan">Bhutan</option>
                                                                <option value="Bolivia">Bolivia</option>
                                                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Bouvet Island">Bouvet Island</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island</option>
                                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                                                <option value="Cook Islands">Cook Islands</option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Cote D'ivoire">Cote D'ivoire</option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Cyprus">Cyprus</option>
                                                                <option value="Czech Republic">Czech Republic</option>
                                                                <option value="Denmark">Denmark</option>
                                                                <option value="Djibouti">Djibouti</option>
                                                                <option value="Dominica">Dominica</option>
                                                                <option value="Dominican Republic">Dominican Republic</option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia</option>
                                                                <option value="French Southern Territories">French Southern Territories</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guernsey">Guernsey</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guinea-bissau">Guinea-bissau</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                                                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="India">India</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jersey">Jersey</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                                                <option value="Korea, Republic of">Korea, Republic of</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libya">Libya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macao">Macao</option>
                                                                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands</option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montenegro">Montenegro</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Myanmar">Myanmar</option>
                                                                <option value="Namibia">Namibia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherlands">Netherlands</option>
                                                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau">Palau</option>
                                                                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Philippines">Philippines</option>
                                                                <option value="Pitcairn">Pitcairn</option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Reunion">Reunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russia">Russia</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="Saint Helena">Saint Helena</option>
                                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                                <option value="Saint Lucia">Saint Lucia</option>
                                                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Serbia">Serbia</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                                <option value="Eswatini">Eswatini</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Timor-leste">Timor-leste</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="United States">United States</option>
                                                                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                                <option value="Uruguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Vietnam">Vietnam</option>
                                                                <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                                <option value="Western Sahara">Western Sahara</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>
                                                            </select>
                                                        </div>
                                                        @error('country')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="first_owner" class="form-label">First Owner</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_owner_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="first_owner" readonly value="{{$client->firstOwner?->username}}" placeholder="First Owner"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="team" class="form-label">Team</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_team_show'))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="team" readonly value="{{ $client->user?->team?->name }}" placeholder="Team"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_deposit_amount" class="form-label">Last Deposit Amount</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_deposite_amount_show'))
                                                        <div class="input-group">
                                                            <input type="text" step="any" class="form-control" readonly id="last_deposit_amount" value="{{ old('last_deposit_amount',number_format($finance['last_deposit_amount'], 2, '.', ',')) }}" placeholder="Last Deposit Amount" />
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_comment_at" class="form-label">First Comment Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_date_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="last_comment_at"  readonly value="{{ $client->comments->count()>0?date('d/m/Y H:i', strtotime($client->comments->first()->created_at)):'' }}" placeholder="Last Comment Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="first_comment_owner" class="form-label">First Comment Owner</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_owner_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="first_comment_owner"  readonly value="{{ $client->comments->count()>0?$client->comments->first()->user->username:'' }}" placeholder="First Comment Owner"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="assigned_at" class="form-label">Assigned Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_date_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="assigned_at" readonly value="{{ $client->assigned_at ? date('d/m/Y H:i', strtotime($client->assigned_at)) : '' }}" placeholder="Assigned Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ftd_date" class="form-label">First Deposit Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_date_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="ftd_date" readonly value="{{ $client->ftd_date ? date('d/m/Y H:i', strtotime($client->ftd_date)) : '' }}" placeholder="FTD Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="created_at" class="form-label">Created Date</label>
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_create_date_show'))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="created_at"  readonly value="{{ date('d/m/Y H:i', strtotime($client->created_at)) }}" placeholder="Created At"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="modified_at" class="form-label">Modified Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_modified_date_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="modified_at"  readonly value="{{ date('d/m/Y H:i', strtotime($client->updated_at)) }}" placeholder="Modified At"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="reg_at" class="form-label">Registration Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_registration_date_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="reg_at" readonly value="{{ date('d/m/Y H:i', strtotime($client->reg_date)) }}" placeholder="Registration Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="age" class="form-label">Age</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_age_show'))
                                                    
                                                        <div class="input-group">
                                                            <input type="number" class="form-control @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_age_edit')) editable @endif" id="age" @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_age_edit')) name="age" @endif value="{{ old('age',$client->age) }}" readonly placeholder="Age" />
                                                        </div>
                                                        @error('age')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade @if ($tab == 'opened') active show @endif" id="opened" role="tabpanel">
                                            <form class="ajax-form" method="GET" data-tab="opened">
                                                <div class="row">
                                                    <div class="d-flex col-md-4"> 
                                                        <?php
                                                    /*
                                                        {{-- <div class="input-group">
                                                            <input type="text" class="form-control from-to-range" id="opened_fromTo" placeholder="{{$opened_fromTo}}">
                                                            <input type="hidden" class="rangeDate" value="{{$opened_fromTo}}" name="opened_fromTo">
                                                        </div> --}}*/
                                                    ?>
                                                        <input type="hidden" value="opened" name="tab">
                                                        <input type="hidden" value="{{$closed_fromTo}}" name="closed_fromTo">
                                                        <input type="hidden" value="{{$moneyTrx_fromTo}}" name="moneyTrx_fromTo">
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="table-responsive mt-2">
                                                <table class="table align-middle mb-0 table-hover opened-order">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>
                                                                <input class="form-check-input me-3 check-all-table" data-target="check-opened" type="checkbox">
                                                                Open Time
                                                            </th>
                                                            <th>Script</th>
                                                            <th>Type</th>
                                                            <th>Amount/Quantity</th>
                                                            <th>Required Margin</th>
                                                            <th>Open price</th>
                                                            <th>Current Price</th>
                                                            <th>Pnl</th>
                                                            <th>Comment</th>
                                                            <th class="text-end">
                                                                <button type="button" formaction="{{ route('main_tp.close_opened_order') }}" class="btn btn-sm text-primary text-center w-auto modal-btn multiClosePositionBtn" data-bs-toggle="modal" data-bs-target="#multiCloseOpenOrderModal" style="background-color: transparent">
                                                                    Close Position
                                                                </button>
                                                                <button type="button" formaction="{{ route('main_tp.delete_order') }}" data-tab="opened" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($opened_data as $opened)
                                                            @php
                                                                $posTypeMapping = [
                                                                    1 => 'Buy',
                                                                    2 => 'Sell',
                                                                ];
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div>
                                                                            <input type="checkbox" class="d-none multi-multiple check-opened" form="deleteForm" name="ids[]" value="{{$opened->id}}">
                                                                            <input class="form-check-input me-3 check-opened multi-multiple" type="checkbox" form="multiClosePosition" name="ids[]" value="{{$opened->id}}" aria-label="...">
                                                                        </div>
                                                                            {{ date('d/m/Y H:i', strtotime($opened->created_at)) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ $opened->asset->name }}
                                                                </td>
                                                                <td class="type" data-order-id="{{$opened->id}}">
                                                                    {{ $posTypeMapping[$opened->type] }}
                                                                </td>
                                                                <td class="amount" data-order-id="{{$opened->id}}">
                                                                    {{$opened->amount}}
                                                                </td>
                                                                <td class="margin" data-order-id="{{$opened->id}}">
                                                                    {{number_format($opened->required_margin,2,'.',',')}}
                                                                </td>
                                                                <td class="open_price" data-order-id="{{$opened->id}}">
                                                                    {{rtrim(rtrim(sprintf('%f', $opened->open_price), '0'), '.')}}
                                                                </td>
                                                                <td class="current_price" data-order-id="{{$opened->id}}">
                                                                    {{rtrim(rtrim(sprintf('%f', $opened->close_price), '0'), '.')}}
                                                                </td>
                                                                <td class="pnl" data-order-id="{{$opened->id}}">
                                                                    <div class="@if ($opened->pnl > 0) text-success @elseif ($opened->pnl < 0) text-danger @else text-dark @endif">
                                                                        {{$opened->pnl}}
                                                                    </div>
                                                                </td>
                                                                <td class="comment" data-order-id="{{$opened->id}}" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                    {!! $opened->comment !!}
                                                                </td>
                                                                <th class="text-end">
                                                                    <button type="button" data-trxdate="{{$opened->created_at}}" data-script="{{ $opened->asset->name }}" data-amount="{{$opened->amount}}" data-comment="{{$opened->comment}}" data-price="{{$opened->open_price}}" data-type="{{$opened->type}}" formaction="{{ route('main_tp.update_open_order', $opened->id) }}" class="btn btn-sm text-primary text-center w-auto modal-btn editOpenOrder" data-order-id="{{$opened->id}}" data-bs-toggle="modal" data-bs-target="#EditOpenOrderModal" style="background-color: transparent">
                                                                        <i class="bx bx-edit"></i>
                                                                    </button>
                                                                    <button type="button" formaction="{{ route('main_tp.close_opened_order', ['id' => $opened->id]) }}" class="btn btn-sm text-primary text-center w-auto modal-btn closePositionBtn" data-bs-toggle="modal" data-bs-target="#closeOpenOrderModal" style="background-color: transparent">
                                                                        Close Position
                                                                    </button>
                                                                    <button type="button" formaction="{{ route('main_tp.delete_order',$opened->id) }}" data-tab="opened" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                        <i class="bx bx-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <br>
                                            @include("layouts.table.pagination.footer",['model' => $opened_data, 'tab' =>'opened'])
                                        </div>
                                        <div class="tab-pane fade @if ($tab == 'closed') active show @endif" id="closed" role="tabpanel">
                                            <form class="ajax-form" method="GET" data-tab="closed">
                                                <div class="row">
                                                    <div class="d-flex col-md-4">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control from-to-range" id="closed_fromTo" placeholder="{{$closed_fromTo}}">
                                                            <input type="hidden" class="rangeDate" value="{{$closed_fromTo}}" name="closed_fromTo">
                                                        </div>
                                                        <input type="hidden" value="closed" name="tab">
                                                        <input type="hidden" value="{{$opened_fromTo}}" name="opened_fromTo">
                                                        <input type="hidden" value="{{$moneyTrx_fromTo}}" name="moneyTrx_fromTo">
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle mb-0 table-hover closed-order">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>
                                                                        <input class="form-check-input me-3 check-all-table" data-target="check-closed" type="checkbox">
                                                                        Open Time
                                                                    </th>
                                                                    <th>Script</th>
                                                                    <th>Type</th>
                                                                    <th>Amount/Quantity</th>
                                                                    <th>Open price</th>
                                                                    <th>Close Time</th>
                                                                    <th>Time Diff</th>
                                                                    <th>Close Price</th>
                                                                    <th>Pnl</th>
                                                                    <th>Comment</th>
                                                                    <th class="text-end">
                                                                        <button type="button" formaction="{{ route('main_tp.delete_order') }}" data-tab="closed" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($closed_data as $closed)
                                                                    @php
                                                                        $posTypeMapping = [
                                                                            1 => 'Buy',
                                                                            2 => 'Sell',
                                                                        ];
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <div>
                                                                                    <input class="form-check-input me-3 check-closed" type="checkbox" form="deleteForm" name="ids[]" value="{{$closed->id}}" aria-label="...">
                                                                                </div>
                                                                                {{ date('d/m/Y H:i', strtotime($closed->created_at)) }}
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            {{ $closed->asset->name }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $posTypeMapping[$closed->type] }}
                                                                        </td>
                                                                        <td>
                                                                            {{$closed->amount}}
                                                                        </td>
                                                                        <td>
                                                                            {{rtrim(rtrim(sprintf('%f', $closed->open_price), '0'), '.')}}
                                                                        </td>
                                                                        <td>
                                                                            @if (isset($closed->closed_at))
                                                                                {{ date('d/m/Y H:i', strtotime($closed->closed_at)) }}
                                                                            @endif
                                                                        </td>
                                                                        <td style="max-width: 100px;min-width: 160px">
                                                                            @if(isset($closed->closed_at))
                                                                                @php
                                                                                    $createdDate = \Carbon\Carbon::parse($closed->closed_at);
                                                                                    $now = \Carbon\Carbon::parse($closed->created_at);
                                                                                    $difference = $createdDate->diff($now);
                                                                                @endphp
                                                                                {{ $difference->days }} d, {{ $difference->h }} h, {{ $difference->i }} m, {{ $difference->s }} s
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            {{rtrim(rtrim(sprintf('%f', $closed->close_price), '0'), '.')}}
                                                                        </td>
                                                                        <td>
                                                                            <div class="{{ (float)($closed->pnl ?? 0.00) > 0 ? 'text-success' : 'text-danger' }}">
                                                                                {{number_format($closed->pnl??0.00, 2, '.', ',');}}
                                                                            </div>
                                                                        </td>
                                                                        <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                            {!! $closed->comment !!}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <button type="button" formaction="{{ route('main_tp.reopen_close_order', $closed->id) }}" class="btn btn-sm text-success text-center w-auto modal-btn reopenClosedOrder" data-bs-toggle="modal" data-bs-target="#reopenCloseOrderModal" style="background-color: transparent">
                                                                                <i class="bx bx-revision"></i>
                                                                            </button>
                                                                            <button type="button" data-type="{{$posTypeMapping[$closed->type]}}" data-script="{{$closed->currency}}" data-amount="{{$closed->amount}}" data-comment="{{$closed->comment??''}}" data-open-price="{{$closed->open_price}}" data-price="{{$closed->close_price}}" data-trxdate="{{$closed->closed_at}}" data-trxopendate="{{$closed->created_at}}" formaction="{{ route('main_tp.update_close_order', $closed->id) }}" class="btn btn-sm text-primary text-center w-auto modal-btn editClosedOrder" data-bs-toggle="modal" data-bs-target="#EditCloseOrderModal" style="background-color: transparent">
                                                                                <i class="bx bx-edit"></i>
                                                                            </button>
                                                                            <button type="button" formaction="{{ route('main_tp.delete_order', $closed->id) }}" data-tab="closed" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                                <i class="bx bx-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <br>
                                                    @include("layouts.table.pagination.footer",['model' => $closed_data, 'tab' =>'closed'])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($tab == 'trx') active show @endif" id="trx" role="tabpanel">
                                            <form class="ajax-form" method="GET" data-tab="trx">
                                                <div class="row">
                                                    <div class="d-flex col-md-4">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control from-to-range" id="moneyTrx_fromTo" placeholder="{{$moneyTrx_fromTo}}">
                                                            <input type="hidden" class="rangeDate" value="{{$moneyTrx_fromTo}}" name="moneyTrx_fromTo">
                                                        </div>
                                                        <input type="hidden" value="trx" name="tab">
                                                        <input type="hidden" value="{{$opened_fromTo}}" name="opened_fromTo">
                                                        <input type="hidden" value="{{$closed_fromTo}}" name="closed_fromTo">
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table align-middle mb-0 table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>
                                                                        <input class="form-check-input me-3 check-all-table" data-target="check-trx" type="checkbox">
                                                                        Create Time
                                                                    </th>
                                                                    <th>Amount</th>
                                                                    <th>Type</th>
                                                                    <th>Comment</th>
                                                                    <th>Payment Details</th>
                                                                    <th>Receipt</th>
                                                                    <th>Created By</th>
                                                                    <th class="text-end">
                                                                        <button type="button" formaction="{{ route('main_tp.delete_money_trx') }}" data-tab="trx" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($money_trx_data as $money_trx)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <div>
                                                                                    <input class="form-check-input me-3 check-trx" type="checkbox" form="deleteForm" name="ids[]" value="{{$money_trx['id']}}" aria-label="...">
                                                                                </div>
                                                                                {{ date('d/m/Y H:i', strtotime($money_trx->created_at)) }}
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            {{number_format($money_trx->amount, 2, '.', ',')}}
                                                                        </td>
                                                                        <td>
                                                                            {{ ucwords($money_trx->type) }}
                                                                        </td>
                                                                        <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                            {!! $money_trx->comment !!}
                                                                        </td>
                                                                        <td>
                                                                            @if ($money_trx->bank_details)
                                                                                Iban                : {{$money_trx->bank_details['iban']}}
                                                                                <br>
                                                                                Swift               : {{$money_trx->bank_details['swift']}}
                                                                                <br>
                                                                                Currency            : {{$money_trx->bank_details['currency']}}
                                                                                <br>
                                                                                Bank Name           : {{$money_trx->bank_details['bank_name']}}
                                                                                <br>
                                                                                Bank Country        : {{$money_trx->bank_details['bank_country']}}
                                                                                <br>
                                                                                Bank Address        : {{$money_trx->bank_details['bank_address']}}
                                                                                <br>
                                                                                Beneficiary Name    : {{$money_trx->bank_details['beneficiary_name']}}
                                                                                <br>
                                                                                Beneficiary Address : {{$money_trx->bank_details['beneficiary_address']}}
                                                                                <br>
                                                                                ABA Routing Number  : {{$money_trx->bank_details['aba_routing_number']}}
                                                                                <br>
                                                                                Beneficiary Country : {{$money_trx->bank_details['beneficiary_country']}}
                                                                            @endif
                                                                            @if ($money_trx->bank_id)
                                                                                Bank Name           : {{$money_trx->bank->name}}
                                                                                <br>
                                                                                Bank Country        : {{$money_trx->bank->country}}
                                                                            @endif
                                                                            {{$money_trx->usdt}}
                                                                        </td>
                                                                        <td>
                                                                            @if ($money_trx->receipt)
                                                                                <a href="{{url($money_trx->receipt)}}" class="btn btn-sm w-auto" style="background-color: transparent" download >
                                                                                    <i class="bx bx-download"></i>
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            {{$money_trx->is_admin ? 'Admin' : 'Client'}}
                                                                        </td>
                                                                        <td class="text-end">
                                                                             @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_money_trx_update'))
                                                                            
                                                                                <button type="button" data-type="{{$money_trx->type}}" data-amount="{{$money_trx->amount}}" data-comment="{{$money_trx->comment}}" data-trxdate="{{$money_trx->created_at}}" formaction="{{ route('main_tp.update_money_trx', $money_trx->id) }}" class="btn btn-sm text-primary text-center w-auto modal-btn edit_money_trx" data-bs-toggle="modal" data-bs-target="#editTransactionModal" style="background-color: transparent">
                                                                                    <i class="bx bx-edit"></i>
                                                                                </button>
                                                                            @endif
                                                                            @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_money_trx_delete'))
                                                                            
                                                                                <button type="button" formaction="{{ route('main_tp.delete_money_trx', $money_trx->id) }}" data-tab="trx" class="btn btn-sm text-danger text-center w-auto modal-btn deleteForm" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                                    <i class="bx bx-trash"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <br>
                                                    @include("layouts.table.pagination.footer",['model' => $money_trx_data, 'tab' =>'trx'])
                                                </div>
                                            </div>
                                        </div>
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions'))
                                        
                                            <div class="tab-pane fade @if ($tab == 'actions') active show @endif" id="actions" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include("layouts.table.pagination.from_to",['type' => 'actions'])
                                                        @include("layouts.table.pagination.header",['model' => $actions, 'tab' =>'actions', 'type' => 'actions'])
                                                        <div class="table-responsive mt-4">
                                                            <table class="table align-middle pagination_table mb-0 table-hover">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Contacts</th>
                                                                        <th>Lead ID</th>
                                                                        <th>Employee</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($actions as $action)
                                                                        @if (strpos($action->text, '<span class="text-primary">Uploaded') === false)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ date('d/m/Y H:i', strtotime($action->created_at)) }}
                                                                                </td>
                                                                                <td>
                                                                                    <a href="{{ route('client.show', $action->client->id) }}">
                                                                                        <h6 class="mb-1 font-14">
                                                                                            {{$action->client->first_name}} {{$action->client->last_name}}
                                                                                        </h6>
                                                                                    </a>
                                                                                </td>
                                                                                <td>
                                                                                    #{{$action->client->id}}
                                                                                </td>
                                                                                <th>
                                                                                    @if ($action->user?->id)
                                                                                        <a @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show')) href="{{ route('user.show',$action->user->id ) }}" @endif >
                                                                                            <h6 class="mb-1 font-14">
                                                                                                {{$action->user->first_name}} {{$action->user->last_name}} ({{$action->user->username}})
                                                                                            </h6>
                                                                                        </a>
                                                                                    @endif
                                                                                </th>
                                                                                <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                                    {!! $action->text !!}
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @include("layouts.table.pagination.footer",['model' => $actions, 'tab' =>'actions'])
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="tab-pane fade @if ($tab == 'history') active show @endif" id="history" role="tabpanel">
                                            <div class="row">
                                                <form action="" id="filter_form_history">
                                                    <input type="hidden" name="tab" value="history">
                                                </form>
                                                <div class="col-12">
                                                    <div class="table-responsive mt-4">
                                                        <table class="table align-middle pagination_table mb-0 table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Contacts</th>
                                                                    <th>Lead ID</th>
                                                                    <th>By</th>
                                                                    <th>Type</th>
                                                                    <th>Part</th>
                                                                    <th>Operation ID</th>
                                                                    <th>Action</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-transparent"><i class='bx bx-calendar-event'></i></span>
                                                                            <input type="text" class="result form-control from-to-range" form="filter_form_history" placeholder="{{ $filters ? ($filters['fromTo_history'] ?? 'Select Date') : 'Select Date' }}">
                                                                            <input type="hidden" class="rangeDate" form="filter_form_history" value="{{ $filters ? ($filters['fromTo_history'] ?? '') : '' }}" name="filters[fromTo_history]">
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" form="filter_form_history" value="{{ $filters ? ($filters['contacts_history'] ?? '') : '' }}" name="filters[contacts_history]" placeholder="Contacts">
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" form="filter_form_history" value="{{ $filters ? ($filters['lead_id_history'] ?? '') : '' }}" name="filters[lead_id_history]" placeholder="Lead ID">
                                                                        </div>
                                                                    </th>
                                                                    <th class="max-w-160">
                                                                        <div class="input-group">
                                                                            <select class="form-select single-select" name="filters[user_id_history]" form="filter_form_history">
                                                                                <option value="">Select User</option>
                                                                                <option value="0">Client</option>
                                                                                @foreach ($users as $user)
                                                                                    <option value="{{$user->id}}" @if (isset($filters['user_id_history']) && $filters['user_id_history'] == $user->id ) selected @endif>{{$user->username}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </th>
                                                                    <th class="max-w-160">
                                                                        <div class="input-group">
                                                                            <select class="form-select single-select" name="filters[type_history]" form="filter_form_history">
                                                                                <option value="">Select Type</option>
                                                                                @foreach (['New','Update','Delete','Close'] as $type)
                                                                                    <option value="{{$type}}" @if (isset($filters['type_history']) && $filters['type_history'] == $type ) selected @endif>{{$type}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </th>
                                                                    <th class="max-w-160">
                                                                        <div class="input-group">
                                                                            <select class="form-select single-select" name="filters[part_history]" form="filter_form_history">
                                                                                <option value="">Select Part</option>
                                                                                @foreach (['Order','Money Transaction', 'Money Transaction Request'] as $part)
                                                                                    <option value="{{$part}}" @if (isset($filters['part_history']) && $filters['part_history'] == $part ) selected @endif>{{$part}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" form="filter_form_history" value="{{ $filters ? ($filters['operation_id_history'] ?? '') : '' }}" name="filters[operation_id_history]" placeholder="Operation ID">
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" form="filter_form_history" value="{{ $filters ? ($filters['action_history'] ?? '') : '' }}" name="filters[action_history]" placeholder="Action">
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <button type="submit" form="filter_form_history" class="btn btn-sm text-primary" style="background-color: transparent">
                                                                            <i class="bx bx-search"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                                @foreach ($money_history as $history)
                                                                    <tr>
                                                                        <td>
                                                                            {{ date('d/m/Y H:i', strtotime($history->created_at)) }}
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('client.show', $history->client->id) }}">
                                                                                <h6 class="mb-1 font-14">
                                                                                    {{$history->client->first_name}} {{$history->client->last_name}}
                                                                                </h6>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            #{{$history->client->id}}
                                                                        </td>
                                                                        <th>
                                                                            @if ($history->user?->id)
                                                                                <a @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show')) href="{{ route('user.show',$history->user->id ) }}" @endif >
                                                                                    <h6 class="mb-1 font-14">
                                                                                        {{$history->user->first_name}} {{$history->user->last_name}} ({{$history->user->username}})
                                                                                    </h6>
                                                                                </a>
                                                                            @elseif($history->user_id == 0)
                                                                                <h6 class="mb-1 font-14">
                                                                                    Client
                                                                                </h6>
                                                                            @endif
                                                                        </th>
                                                                        <th>
                                                                            {{$history->type}}
                                                                        </th>
                                                                        <th>
                                                                            {{$history->part}}
                                                                        </th>
                                                                        <th>
                                                                            {{$history->operation_id}}
                                                                        </th>
                                                                        <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                            {!! $history->text !!}
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @include("layouts.table.pagination.footer",['model' => $money_history, 'tab' =>'history'])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($tab == 'kyc') active show @endif" id="kyc" role="tabpanel">
                                            <div class="row">
                                                <form action="" id="filter_form_kyc">
                                                    <input type="hidden" name="tab" value="kyc">
                                                </form>
                                                <form action="" id="action_kyc" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="tab" value="kyc">
                                                </form>
                                                <div class="col-12">
                                                    <div class="table-responsive mt-4">
                                                        <table class="table align-middle pagination_table mb-0 table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>File</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-transparent"><i class='bx bx-calendar-event'></i></span>
                                                                            <input type="text" class="result form-control from-to-range" form="filter_form_kyc" placeholder="{{ $filters ? ($filters['fromTo_kyc'] ?? 'Select Date') : 'Select Date' }}">
                                                                            <input type="hidden" class="rangeDate" form="filter_form_kyc" value="{{ $filters ? ($filters['fromTo_kyc'] ?? '') : '' }}" name="filters[fromTo_kyc]">
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                    </th>
                                                                    <th class="max-w-160">
                                                                        <div class="input-group">
                                                                            <select class="form-select single-select" name="filters[status_kyc]" form="filter_form_kyc">
                                                                                <option value="">All Status</option>
                                                                                @foreach (['pending','accepted','rejected'] as $kyc_status)
                                                                                    <option value="{{$kyc_status}}" @if (isset($filters['status_kyc']) && $filters['status_kyc'] == $kyc_status ) selected @endif>{{$kyc_status}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <button type="submit" form="filter_form_kyc" class="btn btn-sm text-primary" style="background-color: transparent">
                                                                            <i class="bx bx-search"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                                @foreach ($kycs as $kyc)
                                                                    <tr>
                                                                        <td>
                                                                            {{ date('d/m/Y H:i', strtotime($kyc->created_at)) }}
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{$kyc->path}}" class="btn btn-sm w-auto" style="background-color: transparent" target="_blank" download>
                                                                                <i class="bx bx-download"></i>
                                                                            </a>
                                                                        </td>
                                                                        <td class="{{$kyc->status == 'accepted' ? 'text-success' :''}} {{$kyc->status == 'rejected' ? 'text-danger' :''}} {{$kyc->status == 'pending' ? 'text-warning' :''}}">
                                                                            {{$kyc->status}}
                                                                        </td>
                                                                        <td>
                                                                            @if ($kyc->status == 'pending')
                                                                                <button type="submit" form="action_kyc" formaction="{{ route('main_tp.update_kyc', ['id' => $kyc->id, 'status' => 'accepted']) }}" class="btn btn-sm text-success text-center w-auto" style="background-color: transparent">
                                                                                    <i class="bx bx-check"></i>
                                                                                </button>
                                                                                <button type="submit" form="action_kyc" formaction="{{ route('main_tp.update_kyc', ['id' => $kyc->id, 'status' => 'rejected']) }}" class="btn btn-sm text-danger text-center w-auto" style="background-color: transparent">
                                                                                    <i class="bx bx-x"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @include("layouts.table.pagination.footer",['model' => $kycs, 'tab' =>'kyc'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_comments') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'mainTp_cards_chat'))
                
                    <div class="col-lg-3 col-md-6 col-12 mt-2 comment-tab @if ($tab == 'history') d-none @endif">
                        @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_comments'))
                            @include("client.comments",['client' => $client,'comments' => $comments,'add' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_add_comments')), 'update' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_edit_comments')), 'delete' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_delete_comments'))])
                        @endif
                        <br>
                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_cards_chat'))
                        
                            @include("client.chat",['client' => $client,'chat' => $chat,'add' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_add_chat')), 'update' => true, 'delete' => true])
                        @endif
                    </div>
                @endif
                @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_comments'))
                    <div class="col-lg-2 col-md-6 col-12 mt-2 action-tab @if ($tab == 'history') d-none @endif">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <div>
                                        <h4>Actions</h4>
                                    </div>
                                    <div class="d-flex @if ($client->is_online == true) text-success @else text-warning @endif online-color align-items-center">
                                        <i class="bx bx-radio-circle-marked" style="font-size: 25px"></i>
                                        <div class="online">
                                            @if ($client->is_online == true) Online now @else Offline now @endif
                                        </div>
                                    </div>
                                    <div style="color: #0a81d5;">
                                        Last seen: {{ $client->last_seen_online }}
                                        @if($client->last_seen_at)
                                            <br>
                                            <small>
                                                ({{ \Carbon\Carbon::parse($client->last_seen_at)->format('d/m/Y H:i:s') }})
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <hr class="my-3" />
                                <div class="row text-start">
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions_send_email'))
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#emailModal" style="background-color: transparent">Send Email</button>
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions_create_money_transaction'))
                                    
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#transactionModal" style="background-color: transparent">Create Money Transaction</button>
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions_create_request'))
                                    
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#requestModal" style="background-color: transparent">Create Request</button>
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions_open_order'))
                                    
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" id="fetchScripts" data-bs-toggle="modal" data-bs-target="#openOrderModal" style="background-color: transparent">Open Order</button>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#exportModal" style="background-color: transparent">Export Data</button>
                                    </div>
                                    <hr>
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_actions_Requests'))
                                    
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary position-relative" id="fetchScripts" data-bs-toggle="modal" data-bs-target="#requestsModal" style="background-color: transparent">
                                                Requests
                                                @php
                                                    $total = $moneytrx_request_data->count();
                                                @endphp
                                                @if ($total > 0)
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">@if ($total > 99) +99 @else {{$total}} @endif<span class="visually-hidden">unread messages</span></span>
                                                @endif
                                            </button>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <a href="https://webtrader.bnc-ltd.co.uk/client/webtrader?id={{$client->id}}&token={{auth()->user()->remember_token}}&user_id={{auth()->id()}}" target="_blank" class="btn btn-sm text-primary" style="background-color: transparent">LogIn As Client</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Client Statement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('client.exportData', $client->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <!--<label for="logo" class="form-label">Select Logo for PDF</label>
                            <select class="form-select" name="logo" id="logo" required>
                                <option value="bnc">BNC Logo</option>
                                <option value="phoenix">Phoenix Logo</option>
                            </select>-->
                            <div class="mt-2">
                                <!--<small>Preview:</small>-->
                                <div id="logoPreview" >
                                   @if(isset(Auth::User()->pipeline->logo) && Auth::User()->pipeline->logo != null)
        <img class="logo-img"  src="{{ asset('storage/'.Auth::User()->pipeline->logo) }}" alt="Logo">
        @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="start_date" required
                                value="{{ \Carbon\Carbon::parse($client->created_at)->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" name="end_date" required
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
    
                        <div class="mb-3">
                        <label for="type" class="form-label">Data Type</label>
                            <select class="form-select" name="type" required>
                                <option value="both">Money Transaction & Closed Orders</option>
                                <option value="money_trxes">Money Transactions Only</option>
                                <option value="closed_orders">Closed Orders Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Download PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="requestsModal" tabindex="-1" aria-labelledby="requestsModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 1500px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestsModalLabel">Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive mt-4">
                    <table class="table align-middle data-table-created mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Payment Details</th>
                                <th>Receipt</th>
                                <th>Date/Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($moneytrx_request_data as $request)
                                <tr>
                                    <td>
                                        {{ $request->id }}
                                    </td>
                                    <td>
                                        {{number_format($request->amount, 2, '.', ',');}}
                                    </td>
                                    <td class="text-success">
                                        {{ ucwords($request->type) }}
                                    </td>
                                    <td>
                                        Pending
                                    </td>
                                    <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {!! $request->comment !!}
                                    </td>
                                    <td>
                                        @if ($request->bank_details)
                                            Iban                : {{$request->bank_details['iban']}}
                                            <br>
                                            Swift               : {{$request->bank_details['swift']}}
                                            <br>
                                            Currency            : {{$request->bank_details['currency']}}
                                            <br>
                                            Bank Name           : {{$request->bank_details['bank_name']}}
                                            <br>
                                            Bank Country        : {{$request->bank_details['bank_country']}}
                                            <br>
                                            Bank Address        : {{$request->bank_details['bank_address']}}
                                            <br>
                                            Beneficiary Name    : {{$request->bank_details['beneficiary_name']}}
                                            <br>
                                            Beneficiary Address : {{$request->bank_details['beneficiary_address']}}
                                            <br>
                                            ABA Routing Number  : {{$request->bank_details['aba_routing_number']}}
                                            <br>
                                            Beneficiary Country : {{$request->bank_details['beneficiary_country']}}
                                        @endif
                                        @if ($request->bank_id)
                                            Bank Name           : {{$request->bank->name}}
                                            <br>
                                            Bank Country        : {{$request->bank->country}}
                                        @endif
                                        {{$request->usdt}}
                                    </td>
                                    <td>
                                        @if ($request->receipt)
                                            <a href="{{url($request->receipt)}}" class="btn btn-sm w-auto" style="background-color: transparent" download >
                                                <i class="bx bx-download"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ date('d/m/Y H:i', strtotime($request->created_at)) }}
                                    </td>
                                    <td>
                                        <button type="button" data-date="{{$request->created_at}}" data-bank="{{$request->bank_id}}" data-payment-type="{{$request->bank_details ? 'bank' : ($request->usdt ? 'USDT' : '')}}" data-bank-details="{{json_encode($request->bank_details)}}" data-usdt="{{$request->usdt}}" data-type="{{$request->type}}" data-comment="{{$request->comment}}" data-amount="{{$request->amount}}" formaction="{{ route('main_tp.update_request', ['id' => $request->id]) }}" class="btn btn-sm text-primary text-center w-auto modal-btn editRequest" data-bs-toggle="modal" data-bs-target="#EditRequestModal" style="background-color: transparent">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button type="button" formaction="{{ route('main_tp.handle_request', ['id' => $request->id,'status' => 'accepted']) }}" class="btn btn-sm text-success text-center w-auto handleForm" data-bs-toggle="modal" data-bs-target="#handleRequestModal" style="background-color: transparent">
                                            Accept
                                        </button>
                                        <button type="button" formaction="{{ route('main_tp.handle_request', ['id' => $request->id,'status' => 'rejected']) }}" class="btn btn-sm text-danger text-center w-auto handleForm" data-bs-toggle="modal" data-bs-target="#handleRequestModal" style="background-color: transparent">
                                            Reject
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailLabel">Transfer Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#newEmail" id="view-tab" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-mail-send font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Send New</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#emailHistory" id="view-tab" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-time font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">History</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" id="newEmail" role="tabpanel">
                                    @if ($client->email)
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                Login Email
                                            </div>
                                            <div class="col-6 text-primary">
                                                @if ($client->source == 'BNC')
                                                    no-reply@bnc-ltd.co.uk
                                                @else
                                                    support@phooenixs.com
                                                @endif
                                                @if ($client->account_type == 'Demo')
                                                    <input type="number" form="emailForm" step="any" name="amount" id="demoAmount" class="form-control" placeholder="Amount" required />
                                                @endif
                                            </div>
                                            <div class="col-3 justify-content-end d-flex">
                                                <form class="ajax-form" action="{{ route('email.send', ['id' => $client->id , 'type' => $client->account_type == 'Real'?'real':'demo']) }}" method="POST" id="emailForm">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary my-2" @if(!isset($client->password) || !$client->account_type) disabled @endif>Send</button>
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="emailHistory" role="tabpanel">
                                    @foreach ($email_logs as $email_log)
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{$email_log->created_at}}
                                            </div>
                                            <div class="col">
                                                {{$email_log->user->username}}
                                            </div>
                                            <div class="col">
                                                {{$email_log->type}}
                                            </div>
                                            <div class="col">
                                                <a href="{{ route('email.preview', ['id' => $email_log->id]) }}" class="btn btn-sm w-auto" style="background-color: transparent">
                                                    <i class="bx bx-receipt font-18 text-primary"></i>
                                                </a>
                                            </div>
                                            <div class="col justify-content-end d-flex">
                                                <form class="ajax-form" action="{{ route('email.send', ['id' => $client->id , 'type' => $client->account_type == 'Real'?'real':'demo' , 'client_id' => $client->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Resend</button>
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                                
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Create Money Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="ajax-form" method="POST" action="{{ route('main_tp.create_money_transaction', $client->id) }}" data-tab="trx">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <label for="trxType" class="form-label">Money Transaction type</label>
                                    <div class="input-group">
                                        <select id="trxType" class="single-select form-select inside-modal" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="credit in">Credit In</option>
                                            <option value="credit out">Credit Out</option>
                                            <option value="bonus in">Bonus In</option>
                                            <option value="bonus out">Bonus Out</option>
                                            <option value="deposit">Deposit</option>
                                            <option value="withdraw">Withdraw</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="col">
                                        <input type="number" name="amount" class="form-control" step="any" placeholder="Amount" required />
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <textarea rows="3" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransactionModalLabel">Update Money Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="ajax-form" id="editTrx" method="POST" data-tab="trx">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="userId" value="{{$client->id}}">
                            <div class="row">
                                <div class="col-12">
                                    <label for="editTrxType" class="form-label">Money Transaction type</label>
                                    <div class="input-group">
                                        <select id="editTrxType" class="single-select form-select inside-modal" name="type" required>
                                            <option value="credit in">Credit In</option>
                                            <option value="credit out">Credit Out</option>
                                            <option value="bonus in">Bonus In</option>
                                            <option value="bonus out">Bonus Out</option>
                                            <option value="deposit">Deposit</option>
                                            <option value="withdraw">Withdraw</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="col">
                                        <label for="editTrxAmount" class="form-label">Amount</label>
                                        <input type="number" id="editTrxAmount" name="amount" class="form-control" step="any" placeholder="Amount" required />
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="editTrxComment" class="form-label">Comment</label>
                                    <textarea rows="3" id="editTrxComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="editTrxDate" class="form-label">Transaction Date</label>
                                    <input class="result form-control trxDate" id="editTrxDate" type="text" name="created_at" placeholder="Transction Date">
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Create Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{ route('main_tp.request', $client->broker_id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2">
                                <div class="col-12">
                                    Deposit <input type="radio" class="reqType" name="type" value="deposit">
                                    Withdraw <input type="radio" class="reqType" name="type" value="withdraw">
                                </div>
                                <div class="col-12 requestPaymentType d-none">
                                    USDT <input type="radio" class="reqPaymentType" name="paymentType" value="usdt">
                                    Bank <input type="radio" class="reqPaymentType" name="paymentType" value="bank">
                                </div>
                                <div class="col-12">
                                    <div class="col">
                                        <input type="number" step="any" name="amount" class="form-control" placeholder="Amount" required />
                                    </div>
                                </div>
                                <div class="col-12 requestDeposit d-none">
                                    <label for="requestBankId" class="form-label">Bank</label>
                                    <div class="input-group">
                                        <select id="requestBankId" class="single-select form-select inside-modal" name="bank_id">
                                            <option value="">Select Bank</option>
                                            @foreach ($bank_data as $bank)
                                                <option value="{{$bank->id}}">{{$bank->name}} ({{$bank->country}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 requestDeposit d-none">
                                    <div class="col">
                                        <label for="requestWalletUsdt" class="form-label">USDT Wallet Address</label>
                                        @if ($client->source == 'BNC')
                                            <input type="text" id="requestWalletUsdt" value="{{$client->usdt??auth()->user()->pipeline->usdt['BNC']??''}}" class="form-control" readonly />
                                        @else
                                            <input type="text" id="requestWalletUsdt" value="{{$client->usdt??auth()->user()->pipeline->usdt['phoenix']??''}}" class="form-control" readonly />
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 requestDeposit d-none">
                                    <div class="col">
                                        <label for="requestReceipt" class="form-label">Receipt</label>
                                        <input type="file" id="requestReceipt" class="form-control" name="receipt" />
                                    </div>
                                </div>
                                <div class="col-12 requestUsdt d-none">
                                    <label for="requestUsdt" class="form-label">USDT</label>
                                    <input type="text" id="requestUsdt" name="usdt" class="form-control" placeholder="Wallet Address"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestIban" class="form-label">Iban</label>
                                    <input type="text" id="requestIban" name="bank_details[iban]" class="form-control" placeholder="Iban"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestSwift" class="form-label">Swift</label>
                                    <input type="text" id="requestSwift" name="bank_details[swift]" class="form-control" placeholder="Swift"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestCurrency" class="form-label">Currency</label>
                                    <input type="text" id="requestCurrency" name="bank_details[currency]" class="form-control" placeholder="Currency"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestBankName" class="form-label">Bank Name</label>
                                    <input type="text" id="requestBankName" name="bank_details[bank_name]" class="form-control" placeholder="Bank Name"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestBankCountry" class="form-label">Bank Country</label>
                                    <input type="text" id="requestBankCountry" name="bank_details[bank_country]" class="form-control" placeholder="Bank Country"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestBankAddress" class="form-label">Bank Address</label>
                                    <input type="text" id="requestBankAddress" name="bank_details[bank_address]" class="form-control" placeholder="Bank Address"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestBeneficiaryName" class="form-label">Beneficiary Name</label>
                                    <input type="text" id="requestBeneficiaryName" name="bank_details[beneficiary_name]" class="form-control" placeholder="Beneficiary Name"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestBeneficiaryAddress" class="form-label">Beneficiary Address</label>
                                    <input type="text" id="requestBeneficiaryAddress" name="bank_details[beneficiary_address]" class="form-control" placeholder="Beneficiary Address"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestAbaRoutingNumber" class="form-label">Aba Routing Number</label>
                                    <input type="text" id="requestAbaRoutingNumber" name="bank_details[aba_routing_number]" class="form-control" placeholder="Aba Routing Number"/>
                                </div>
                                <div class="col-md-6 request_bank_details d-none">
                                    <label for="requestBeneficiaryCountry" class="form-label">Beneficiary Country</label>
                                    <input type="text" id="requestBeneficiaryCountry" name="bank_details[beneficiary_country]" class="form-control" placeholder="Beneficiary Country"/>
                                </div>
                                
                                <div class="col-12">
                                    <textarea rows="3" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-success">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="openOrderModal" tabindex="-1" aria-labelledby="openOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="openOrderModalLabel">Open New Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="ajax-form" method="POST" action="{{ route('main_tp.open_order', $client->broker_id) }}" data-tab="opened">
                            @csrf
                            <div class="alert alert-danger d-none" id="error_message"></div>
                            <div class="row" id="open_order_section">
                                <div class="col-md-6">
                                    <label for="posCurrencyId" class="form-label">Script</label>
                                    <div class="input-group">
                                        <select id="posCurrencyId" class="single-select form-select inside-modal" name="currency" required>
                                            <option value="0" data-bid="0.00" data-ask="0.00">Select Script</option>;
                                            @foreach ($scripts_data as $asset)
                                                <option value="{{$asset->id}}" data-bid="{{$asset->bid_price??0.00}}" data-percentage="{{($asset->groupAssignments->first()->is_percentage??0) == 0 ? 0 : 1}}" data-ask="{{$asset->ask_price??0.00}}"
                                                    data-contract-size="{{$asset->groupAssignments->first()->size??0.00}}" data-leverage="{{$asset->groupAssignments->first()->leverage??0.00}}" data-base="{{$asset->currency??0.00}}" data-symbol="{{$asset->symbol}}">
                                                    {{$asset->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="posType" class="form-label">Type</label>
                                    <div class="input-group">
                                        <select id="posType" class="single-select form-select inside-modal" name="type" required>
                                            <option value="1" selected>Buy</option>
                                            <option value="2">Sell</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <div class="col">
                                            <label for="posAmount" class="form-label">Amount/Lot</label>
                                            <input type="number" id="posAmount" name="amount" value="0.01" step="any" min="0.01" class="form-control" placeholder="Amount" required />
                                            <div class="form-text text-danger d-none min-amount">Minimum amount is 0.01</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="posPrice" class="form-label">Price</label>
                                    <input type="number" name="open_price" id="posPrice" class="form-control" placeholder="Price" step="any" value="0" required />
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="reqMargin" class="form-label">Required Margin</label>
                                    <input class="result form-control" type="text" id="reqMargin" name="required_margin" value="0" placeholder="Required Margin" readonly>
                                </div>
                                <div class="col-12 mt-2">
                                    <textarea rows="3" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success openOrderBtn">Open</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closeOpenOrderModal" tabindex="-1" aria-labelledby="closeOpenOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeOpenOrderModalLabel">Close Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        Are you sure you want to close this order?
                        <form class="ajax-form" method="POST" id="closePosition" data-tab="opened">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-12 mt-2">
                        <button type="submit" form="closePosition" class="btn btn-sm btn-success closeOpenOrderBtn">Submit</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditCloseOrderModal" tabindex="-1" aria-labelledby="EditCloseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditCloseOrderModalLabel">Update Closed Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="ajax-form-no-reload" method="POST" id="editClosePosition" data-tab="closed">
                            @csrf
                            @method('PUT')
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="editScript" class="form-label">Script</label>
                                    <input type="text" id="editScript" value="Unknown" class="form-control" placeholder="Script" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label for="closeType" class="form-label">Type</label>
                                    <input type="text" id="closeType" value="Unknown" class="form-control" placeholder="Type" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label for="editCloseAmount" class="form-label">Close Amount</label>
                                    <input type="number" id="editCloseAmount" name="amount" value="0.01" step="any" class="form-control" placeholder="Amount" required/>
                                    <div class="form-text text-danger d-none min-amount-close">Minimum amount is 0.01</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="openPrice" class="form-label">Open Price</label>
                                    <input type="number" id="openPrice" name="open_price" value="0.01" step="any" class="form-control" placeholder="Open Price" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="closePrice" class="form-label">Close Price</label>
                                    <input type="number" id="closePrice" name="close_price" value="0.01" step="any" class="form-control" placeholder="Close Price" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="closeTrxOpenDate" class="form-label">Opened Time</label>
                                    <input class="result form-control trxDate" id="closeTrxOpenDate" type="text" name="created_at" placeholder="Transction Date">
                                </div>
                                <div class="col-md-6">
                                    <label for="closeTrxDate" class="form-label">Closed Time</label>
                                    <input class="result form-control trxDate" id="closeTrxDate" type="text" name="closed_at" placeholder="Transction Date">
                                </div>
                                <div class="col-12">
                                    <label for="closeComment" class="form-label">Close Comment</label>
                                    <textarea rows="3" id="closeComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-success UpdateClosedOrder">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reopenCloseOrderModal" tabindex="-1" aria-labelledby="reopenCloseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reopenCloseOrderModalLabel">Reopen Closed Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        Are you sure you want to reopen this order?
                        <form class="ajax-form" method="POST" id="reopenClosePosition" data-tab="closed">
                            @csrf
                            @method('PUT')
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-12 mt-2">
                        <button type="submit" form="reopenClosePosition" class="btn btn-sm btn-success reopenOredBtn">Submit</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditOpenOrderModal" tabindex="-1" aria-labelledby="EditOpenOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditOpenOrderModalLabel">Update Open Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="ajax-form-no-reload" method="POST" id="editOpenPosition" data-tab="opened">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <label for="editPosScript" class="form-label">Script</label>
                                    <input type="text" id="editPosScript" value="Unknown" class="form-control" placeholder="Script" readonly/>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="editPosType" class="form-label">Type</label>
                                    <div class="input-group">
                                        <select id="editPosType" class="single-select form-select inside-modal" name="type" required>
                                            <option value="1">Buy</option>
                                            <option value="2">Sell</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="editPosAmount" class="form-label">Open Amount</label>
                                    <input type="number" id="editPosAmount" name="amount" value="0.01" min="0.01" step="any" class="form-control" placeholder="Amount" required/>
                                    <div class="form-text text-danger d-none min-amount-open">Minimum amount is 0.01</div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="editPosPrice" class="form-label">Open Price</label>
                                    <input type="number" id="editPosPrice" name="open_price" value="0.01" step="any" class="form-control" placeholder="Price" required/>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="posDate" class="form-label">Open Date</label>
                                    <input class="result form-control trxDate" id="posDate" type="text" name="created_at" placeholder="Transction Date">
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="posComment" class="form-label">Comment</label>
                                    <textarea rows="3" id="posComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success updateOrderBtn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditRequestModal" tabindex="-1" aria-labelledby="EditRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditRequestModalLabel">Update Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="ajax-form" method="POST" id="EditRequest">
                            @csrf
                            @method('PUT')
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="editRequestAmount" class="form-label">Amount</label>
                                    <input type="number" id="editRequestAmount" name="amount" value="0.01" step="any" class="form-control" placeholder="Amount" required/>
                                </div>
                                <div class="col-md-6 deposit d-none">
                                    <label for="bankId" class="form-label">Bank</label>
                                    <div class="input-group">
                                        <select id="bankId" class="single-select form-select inside-modal" name="bank_id">
                                            <option value="">Select Bank</option>
                                            @foreach ($bank_data as $bank)
                                                <option value="{{$bank->id}}">{{$bank->name}} ({{$bank->country}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="editRequestDate" class="form-label">Request Date</label>
                                    <input class="result form-control trxDate" id="editRequestDate" type="text" name="created_at" placeholder="Transction Date" required/>
                                </div>
                                <div class="col-12 usdt d-none">
                                    <label for="editRequestUsdt" class="form-label">USDT</label>
                                    <input type="text" id="editRequestUsdt" name="usdt" class="form-control" placeholder="Wallet Address"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestIban" class="form-label">Iban</label>
                                    <input type="text" id="editRequestIban" name="bank_details[iban]" class="form-control" placeholder="Iban"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestSwift" class="form-label">Swift</label>
                                    <input type="text" id="editRequestSwift" name="bank_details[swift]" class="form-control" placeholder="Swift"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestCurrency" class="form-label">Currency</label>
                                    <input type="text" id="editRequestCurrency" name="bank_details[currency]" class="form-control" placeholder="Currency"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestBankName" class="form-label">Bank Name</label>
                                    <input type="text" id="editRequestBankName" name="bank_details[bank_name]" class="form-control" placeholder="Bank Name"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestBankCountry" class="form-label">Bank Country</label>
                                    <input type="text" id="editRequestBankCountry" name="bank_details[bank_country]" class="form-control" placeholder="Bank Country"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestBankAddress" class="form-label">Bank Address</label>
                                    <input type="text" id="editRequestBankAddress" name="bank_details[bank_address]" class="form-control" placeholder="Bank Address"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestBeneficiaryName" class="form-label">Beneficiary Name</label>
                                    <input type="text" id="editRequestBeneficiaryName" name="bank_details[beneficiary_name]" class="form-control" placeholder="Beneficiary Name"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestBeneficiaryAddress" class="form-label">Beneficiary Address</label>
                                    <input type="text" id="editRequestBeneficiaryAddress" name="bank_details[beneficiary_address]" class="form-control" placeholder="Beneficiary Address"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestAbaRoutingNumber" class="form-label">Aba Routing Number</label>
                                    <input type="text" id="editRequestAbaRoutingNumber" name="bank_details[aba_routing_number]" class="form-control" placeholder="Aba Routing Number"/>
                                </div>
                                <div class="col-md-6 bank_details d-none">
                                    <label for="editRequestBeneficiaryCountry" class="form-label">Beneficiary Country</label>
                                    <input type="text" id="editRequestBeneficiaryCountry" name="bank_details[beneficiary_country]" class="form-control" placeholder="Beneficiary Country"/>
                                </div>
                                <div class="col-12">
                                    <label for="requestComment" class="form-label">Comment</label>
                                    <textarea rows="3" id="requestComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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
                <form class="ajax-form" method="POST" id="deleteForm">
                    @csrf
                    @method('delete')
                </form>
                <button type="submit" form="deleteForm" class="btn btn-danger deleteBtn">Delete</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="multiCloseOpenOrderModal" tabindex="-1" aria-labelledby="multiCloseOpenOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multiCloseOpenOrderModalLabel">Close Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        Are you sure you want to close selected orders?
                        <form class="ajax-form" method="POST" id="multiClosePosition" data-tab="opened">
                            <input type="hidden" name="tab" value="opened">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-12 mt-2">
                        <button type="submit" form="multiClosePosition" class="btn btn-sm btn-success">Submit</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_yes_no'))

        <div class="modal fade" id="optionsModal" tabindex="-1" aria-labelledby="optionsModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionsModalLabel">Yes/No Properties</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('mainTp.update_yes_no', $client->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[isEnabled]" value="1" @isset($client->options['isEnabled']) checked @endisset />&nbsp;Is Enabled
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[ignoreLiquidation]" @isset($client->options['ignoreLiquidation']) checked @endisset value="1"/>&nbsp;Ignore Liquidation
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[forceChangePassword]" @isset($client->options['forceChangePassword']) checked @endisset value="1" />&nbsp;Force Change Password
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[cantOpen]" @isset($client->options['cantOpen']) checked @endisset value="1" />&nbsp;Cant Open
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[cantClose]" @isset($client->options['cantClose']) checked @endisset value="1" />&nbsp;Cant Close
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[noCloseAtLoss]" @isset($client->options['noCloseAtLoss']) checked @endisset value="1" />&nbsp;No Close At Loss
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[enableWithdrawalRequest]" @isset($client->options['enableWithdrawalRequest']) checked @endisset value="1" />&nbsp;Enable Withdrawal Request
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[enableDepositRequest]" @isset($client->options['enableDepositRequest']) checked @endisset value="1" />&nbsp;Enable Deposit Request
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[canWithdrawalCredit]" @isset($client->options['canWithdrawalCredit']) checked @endisset value="1" />&nbsp;Can Withdrawal Credit
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[canWithdrawalBonus]" @isset($client->options['canWithdrawalBonus']) checked @endisset value="1" />&nbsp;Can Withdrawal Bonus
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[isVerified]" @isset($client->options['isVerified']) checked @endisset value="1" />&nbsp;Is Verified
                                    </div>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="is_ftd" name="options[disable_webtrader]" @isset($client->options['disable_webtrader']) checked @endisset value="1" />&nbsp;Disable Webtrader
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                        <button type="submit" formaction="{{ route('mainTp.update_yes_no', ['id' => $client->id, 'def' => 1]) }}" class="btn btn-sm btn-warning">Reset to default</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="handleRequestModal" tabindex="-1" aria-labelledby="handleRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="handleRequestModalLabel">Handle Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="handleForm" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="reqComment" class="form-label">Comment</label>
                                        <textarea rows="3" id="reqComment" class="form-control" name="comment" placeholder="Type Comment..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </div>
            </div>
        </div>
@endsection
@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-date-time-pickers.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    <script src="{{ asset('assets/js/main_tp.js') }}"></script>
    <script>
        var broker_id          = {{ $client->broker_id ?? 'null' }};
        var client_id          = {{ $client->id }};
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'mainTp_can_update'))
    
        <script>
            $('#edit_btn').on('click', function() {
                $('.editable').removeAttr('readonly disabled');
                $('#edit_btn').addClass('d-none');
                $('.generate-password').removeClass('d-none');
                $('#cancel_btn, #save_btn').removeClass('d-none');
            });
        </script>
    @endif

    <script>
        const logoMap = {
            bnc: "{{ asset('assets/images/bnc.png') }}",
            phoenix: "{{ asset('assets/images/phoenix.png') }}",
        };
        document.getElementById('logo').addEventListener('change', function() {
            const val = this.value;
            document.getElementById('logoPreviewImg').src = logoMap[val] || logoMap['bnc'];
    });
</script>
@endsection
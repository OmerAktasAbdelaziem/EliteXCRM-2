@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css?v2.944') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v2.944') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v2.944') }}">
    {{-- textEditor --}}
    <link rel="stylesheet" href="{{ url('assets/plugins/external/sample/css/sample.css?v2.944') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/external/dist/css/suneditor.min.css?v2.944') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.49.0/lib/codemirror.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.11.1/dist/katex.min.css">
    <style>
        .dcalendarpicker .dudp__wrapper {
            top: 24px !important;
            bottom: unset !important;
        }
        .chat-content{
            height: 376px!important;
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
        @media (min-width: 995px) {
            .h-100 .chat-content{
                height: 90% !important;
            }
        }
        .card {
            margin-bottom:0 !important; 
        }
        .text-red{
            color: #af0000;
        }
        .compose-mail-popup {
            bottom: 0px;
        }
        .se-wrapper-inner{
            max-height:300px ;
        }
    </style>
@endsection
<?php 
/*
@section('title',
    (($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show')) ? $client->first_name : '') . ' ' .
    (UserPermission::isSuperAdmin(Auth::user()) ||  UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show')) ? $client->last_name : '')
)
*/ ?>
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="@if($client->broker_id && !isset($client->options['isEnabled']))background-color: #f14f5e;@else background-color: #0d6efd; @endif margin-bottom:0;border-radius: 0;box-shadow: none !important">
                        <div class="card-body" style="padding-bottom: 5px">
                            <div class="row text-white">
                                <div class="col-md-2 col-6">
                                    <small class="form-label">Name</small>
                                    <h3 class="text-white mb-3">
                                        @if ($client->is_renew)
                                            <i class="text-red bx bx-caret-down h2 mb-0"></i>
                                        @endif
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show') )
                                            {{$client->first_name}}
                                        @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_hide'))
                                            {{ substr($client->first_name, 0, ceil(strlen($client->first_name) / 2)) }}******
                                        @else
                                            ******
                                        @endif
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show') )
                                            {{$client->last_name}}
                                        @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_hide'))
                                            {{ substr($client->last_name, 0, ceil(strlen($client->last_name) / 2)) }}******
                                        @else
                                            ******
                                        @endif
                                    </h3>
                                </div>
                                <div class="col-md-1 col-6">
                                    <small class="form-label">ID</small>
                                    <h4>
                                        <a class="text-white" href="{{ route('client.show', $client->id) }}" target="_blank" rel="noopener noreferrer">{{$client->id}}</a>
                                    </h4>
                                </div>
                                
                                @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_main_tp') || (UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_main_tp_demo') && $client->account_type == 'Demo') && Auth::user()->pipeline->category_id == 1)
                                
                                    <div class="col-md-1 col-6">
                                        <small class="form-label">TP</small>
                                        <h4>
                                            @if ($client->broker_id)
                                                <a class="text-white" href="{{ route('main_tp.show', $client->id) }}">{{$client->broker_id}}</a>
                                            @else
                                                <div class="text-white">
                                                    -
                                                </div>
                                            @endif
                                        </h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-1 mt-0">
                <div class="col-6">
                    @if ($pre == 1)
                        <a href="{{ route('client.slides', ['id'=>$client->id, 'move'=>'Previous', 'status' => $status]) }}" class="btn btn-primary text-center text-white w-auto" style="border-radius: 0 0 0.25rem 0.25rem">
                            <i class="bx bx-left-arrow-alt me-2"></i>
                            Previous
                        </a>
                    @else
                        <a class="btn btn-primary text-center text-white w-auto" style="border-radius: 0 0 0.25rem 0.25rem">
                            <i class="bx bx-upside-down"></i>
                        </a>
                    @endif
                </div>
                <div class="col-6 text-end">
                    @if ($next == 1)
                        <a href="{{ route('client.slides', ['id'=>$client->id,'move'=>'Next', 'status' => $status]) }}" class="btn btn-primary text-center text-white w-auto" style="border-radius: 0 0 0.25rem 0.25rem">
                            Next
                            <i class="bx bx-right-arrow-alt me-2"></i>
                        </a>
                    @else
                        <a class="btn btn-primary text-center text-white w-auto" style="border-radius: 0 0 0.25rem 0.25rem">
                            <i class="bx bx-upside-down"></i>
                        </a>
                    @endif
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
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'info') active @endif" data-bs-toggle="tab" href="#show" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-show font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Information</div>
                                                </div>
                                            </a>
                                        </li>
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_actions_actions') )
                                        
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link @if ($tab == 'actions') active @endif" data-bs-toggle="tab" href="#actions" id="view-tab" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class="bx bx-history font-18 me-1"></i>
                                                        </div>
                                                        <div class="tab-title">Actions</div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
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
                                            @if ($client->broker_id)
                                                <div class="row">
                                                    @if (!isset($client->options['enableWithdrawalRequest']) || !isset($client->options['enableDepositRequest']) || !isset($client->options['isEnabled']))
                                                        <div class="alert alert-danger">
                                                            Missing Default Options!!
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            <form class="row g-3" action="{{ route('client.update',$client->id) }}" method="POST" name="addform" id="addform" >
                                                @csrf
                                                @method('PUT')
                                                <div class="col-12 text-end">
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_renew') )
                                                        <button type="button" class="btn p-0" style="background-color: transparent" data-bs-toggle="modal" data-bs-target="#renewModal">
                                                            <i class="text-danger bx bx-refresh h5 mb-0"></i>
                                                        </button>
                                                    @endif
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_edit') )
                                                    
                                                        <button type="button" id="edit_btn" class="btn p-0" style="background-color: transparent"><i class="text-primary bx bx-edit h5 mb-0"></i></button>
                                                        <a href="{{ route('client.show', $client->id) }}" type="button" id="cancel_btn" class="btn p-0 d-none" style="background-color: transparent"><i class="text-secondary bx bx-x h5 mb-0"></i></a>
                                                        <button type="submit" id="save_btn" class="btn p-0 d-none" style="background-color: transparent"><i class="text-success bx bx-check h5 mb-0"></i></button>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show') )
                                                        <div class="input-group">
                                                            <input type="text" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_edit') ) name="first_name" @endif readonly value="{{ old('first_name',$client->first_name) }}" class="form-control @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_edit') ) editable @endif" id="first_name" placeholder="First Name"  />
                                                        </div>
                                                        @error('first_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_hide'))
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show') )
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_edit') ) editable @endif" id="last_name" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_edit') ) name="last_name" @endif readonly value="{{ old('last_name',$client->last_name) }}" placeholder="Last Name"/>
                                                        </div>
                                                        @error('last_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_hide'))
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
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_show') )
                                                        <div class="input-group">
                                                            <input type="mail" class="form-control @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_edit') ) editable @endif" id="email" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_edit') ) name="email" @endif value="{{ old('email',$client->email) }}" readonly placeholder="Email Address" />
                                                        </div>
                                                        @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_hide'))
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
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_show') )
                                                        <div class="input-group">
                                                            <input type="tel" class="form-control @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_edit') ) editable @endif" id="phone1" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_edit') ) name="phone1" @endif readonly value="{{ old('phone1',$client->phone1) }}" placeholder="Primary Number"/>
                                                        </div>
                                                        @error('phone1')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        
                                                    @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_hide'))
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="tel" class="form-control @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_edit') ) editable @endif" id="phone2" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_edit') ) name="phone2" @endif readonly value="{{ old('phone2',$client->phone2) }}" placeholder="Secondary Number"/>
                                                        </div>
                                                        @error('phone2')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_hide'))
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
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_show') )
                                                        <div class="input-group">
                                                            <select id="sales_status" class="single-select form-select @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit') ) editable @endif" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit') ) name="sales_status" @endif disabled>
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
                                                    <label for="is_ftd" class="form-label">FTD</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_status_show') )
                                                    
                                                        <div class="form-check form-switch p-0 pt-2" style="display: flex; flex-wrap: wrap;">
                                                            <label class="form-check-label" for="is_ftd" style="order: 1; margin-right: 45px;">Inactive</label>
                                                            <input class="form-check-input @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_status_edit') ) editable @endif" disabled value="1" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_status_edit') ) name="is_ftd" @endif type="checkbox" id="is_ftd" style="order: 2; margin-right: 10px;" @if (old('is_ftd',$client->is_ftd) == true) checked @endif>
                                                            <label class="form-check-label" for="is_ftd" style="order: 3; margin-right: 10px;">Active</label>
                                                        </div>
                                                        @error('is_ftd')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="enabled" class="form-label">Enabled</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_leads_enabled_show') )
                                                    
                                                        <div class="form-check form-switch p-0 pt-2" style="display: flex; flex-wrap: wrap;">
                                                            <label class="form-check-label" for="enabled" style="order: 1; margin-right: 45px;">Inactive</label>
                                                            <input class="form-check-input" disabled value="1" type="checkbox" id="enabled" style="order: 2; margin-right: 10px;" @if ($client->broker_id && $client->account_type == 'Real') checked @endif>
                                                            <label class="form-check-label" for="enabled" style="order: 3; margin-right: 10px;">Active</label>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label id="user_id" class="form-label">Assigned User</label>
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_show') )
                                                        <div class="input-group">
                                                            <select id="user_id" class="single-select form-select @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_edit') ) editable @endif" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_edit') ) name="user_id" @endif disabled>
                                                                <option value="" >Select User</option>
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_username_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username',$client->username) }}" readonly placeholder="Username" />
                                                        </div>
                                                        @error('username')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @elseif(UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_username_hide'))
                                                        <div>
                                                            {{ substr($client->username, 0, ceil(strlen($client->username) / 2)) }}******
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">Password</label>
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_password_show') )
                                                        <div class="input-group">
                                                            <input type="text" class="form-control password" id="password" readonly name="password" value="{{ old('password',$client->password_text) }}"/>
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
                                                <div class="col-md-6">
                                                    <label for="ftd_amount" class="form-label">FTD Amount</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_amount_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control @if(Auth::user()->pipeline->category_id == 2) editable @endif" readonly id="ftd_amount" name="ftd_amount" value="{{ old('ftd_amount',$api_data['ftd_amount']) }}" placeholder="Amount" />
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="account_type" class="form-label">Account type</label>
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_account_type_show') )
                                                        <div class="input-group">
                                                            <select id="account_type" class="single-select form-select @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_account_type_edit') ) && $client->broker_id) editable @endif" name="account_type" disabled>
                                                                <option value="" @if (!$client->broker_id) selected @endif></option>
                                                                <option value="Real" @if ($client->broker_id && $client->account_type == 'Real') selected @endif>Real</option>
                                                                <option value="Demo" @if ($client->broker_id && $client->account_type == 'Demo') selected @endif>Demo</option>
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_asset_group_show') )
                                                    
                                                        <div class="input-group">
                                                            <select id="asset_group_id" class="single-select form-select @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_asset_group_edit') ) editable @endif" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_asset_group_edit') ) name="asset_group_id" @endif disabled>
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
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_show') )
                                                        <div class="input-group">
                                                            <select id="country" class="single-select form-select @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_edit') ) editable @endif"  @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_edit') ) name="country" @endif disabled>
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_owner_show') )
                                                    
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_team_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="team"  readonly value="{{ $client->user?->team?->name }}" placeholder="Team"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_deposit_amount" class="form-label">Last Deposit Amount</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_deposite_amount_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" step="any" class="form-control" readonly id="last_deposit_amount" value="{{ number_format(0.00, 2, '.', ',') }}" placeholder="Last Deposit Amount" />
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="first_comment_at" class="form-label">First Comment Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_date_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="first_comment_at"  readonly value="{{ $client->comments->count()>0?date('d/m/Y H:i', strtotime($client->comments->first()->created_at)):'' }}" placeholder="Last Comment Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="first_comment_owner" class="form-label">First Comment Owner</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_owner_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="first_comment_owner"  readonly value="{{ $client->comments->count()>0?$client->comments->first()->user->username:'' }}" placeholder="First Comment Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="assigned_at" class="form-label">Assigned Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_date_show') )
                                                    
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
                                                    <label for="ftd_date" class="form-label">FTD Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_date_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="ftd_date"  readonly value="{{ $client->is_ftd?date('d/m/Y H:i', strtotime($client->ftd_date)):'' }}" placeholder="FTD Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="created_at" class="form-label">Created Date</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_create_date_show') )
                                                    
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_modified_date_show') )
                                                    
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
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_registration_date_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="reg_at" readonly value="{{ $client->reg_date?date('d/m/Y H:i', strtotime($client->reg_date)):'' }}" placeholder="Registration Date"/>
                                                        </div>
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="age" class="form-label">Age</label>
                                                    
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_age_show') )
                                                        <div class="input-group">
                                                            <input type="number" class="form-control @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_age_edit') ) editable @endif" id="age" @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_age_edit') ) name="age" @endif value="{{ old('age',$client->age) }}" readonly placeholder="Age" />
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
                                                <div class="col-md-6">
                                                    <label for="created_by" class="form-label">Created By</label>
                                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_created_by_show') )
                                                    
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="created_by" readonly value="{{ $client->created_by ?? 'N/A' }}" placeholder="Created By" />
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_actions_actions') && UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_actions')))
                                        
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
                                                                                        <a @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show') ) href="{{ route('user.show',$action->user->id ) }}" @endif >
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
                        <div class="col-12 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_campaign_show')  )
                                        <div class="col-md-4">
                                            <small class="form-label">Campaign</small>
                                            <h5>
                                                <span class="input-group-text bg-transparent">
                                                    <i class='bx bx-repost'></i> &nbsp;
                                                    {{$client->campaign}}
                                                </span>
                                            </h5>
                                        </div>
                                        @endif
                                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_source_show')  )
                                        <div class="col-md-4">
                                            <small class="form-label">Source</small>
                                            <h5>
                                                <span class="input-group-text bg-transparent">
                                                    <i class='bx bx-repost'></i> &nbsp;
                                                    {{$client->source}}
                                                </span>
                                            </h5>
                                        </div>
                                        @endif
                                        <div class="col-md-4">
                                            <small class="form-label">Ad</small>
                                            <h5>
                                                <span class="input-group-text bg-transparent">
                                                    <i class='bx bx-repost'></i> &nbsp;
                                                    {{$client->ad}}
                                                </span>
                                            </h5>
                                        </div>
                                        <div class="col-6">
                                            <a target="blank" href="{{ route('client.moreInfo', $client->id) }}">More Information</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_comments') )
                
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        @include("client.comments",['client' => $client,'comments' => $comments,'add' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_add_comments') ), 'update' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_edit_comments') ), 'delete' => ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_delete_comments') )])
                    </div>
                @endif
                @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_actions') )
                
                    <div class="col-lg-2 col-md-6 col-12 mt-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <div>
                                        <h4>Actions</h4>
                                    </div>
                                    {{-- <div class=" @if ($client->is_online == true) text-success @else text-warning @endif online">
                                        @if ($client->is_online == true) Online now @else Offline now @endif
                                    </div> --}}
                                </div>
                                <hr class="my-3" />
                                <div class="row text-start">
                                    @if (!$client->broker_id && $client->first_name &&  ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_actions_open_real') ) && $client->phone1 && $client->email != null && $client->email != '' && $client->email != ' ')
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#realModal" style="background-color: transparent">Open Real Account</button>
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'send_emails') )
                                    
                                        <div class="col-12">
                                            <a href="javascript:;" class="btn btn-sm compose-mail-btn text-primary" style="background-color: transparent">
                                                Send Marketing Email
                                            </a>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#marketingEmailLogsModal" style="background-color: transparent">Marketing Email Log</button>
                                    </div>
                                    @if ($client->email && $client->phone1 && $client->first_name && ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_actions_open_demo') ) && !$client->broker_id && $client->email != null && $client->email != '' && $client->email != ' ')
                                        <hr>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#demoModal" style="background-color: transparent">Open Demo</button>
                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        @if (($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_actions_open_demo') && UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_actions') )))
                            <div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="demoModalLabel">Open Demo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <form method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" name="username" class="form-control" placeholder="username" required />
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control password" name="password" value="" placeholder="password" required/>
                                                                <button class="btn generate-password" style="border:1px solid #ced4da;" title="Generate Password" type="button"><i class='bx bx-reset'></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" name="amount" class="form-control" placeholder="Amount"/>
                                                        </div>
                                                        <div class="col-12 mt-1">
                                                            <input type="checkbox" class="is_ftd" checked name="forceChangePassword" value="1" />&nbsp;Force Change Password
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col" style="align-content: center;">
                                                                <button type="submit" formaction="{{ route('clients.demo', ['id' => $client->id]) }}" class="btn text-primary font-20 p-0" style="background-color: transparent">Open Demo Account</button>
                                                            </div>
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
                        
                        @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_actions_open_real') && UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_actions') ))
                            <div class="modal fade" id="realModal" tabindex="-1" aria-labelledby="realModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="realModalLabel">Open Account</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="{{ route('clients.real', ['id' => $client->id]) }}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" name="username" class="form-control" placeholder="username" required />
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control password" name="password" value="" placeholder="password" required/>
                                                                <button class="btn generate-password" style="border:1px solid #ced4da;" title="Generate Password" type="button"><i class='bx bx-reset'></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-1">
                                                            <input type="checkbox" class="is_ftd" checked name="forceChangePassword" value="1" />&nbsp;Force Change Password
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col" style="align-content: center;">
                                                            <button type="submit" class="btn text-primary font-20 p-0" style="background-color: transparent">Open Real Account</button>
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
                        <div class="modal fade" id="marketingEmailLogsModal" tabindex="-1" aria-labelledby="marketingEmailLogsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" style="max-width: 1000px;">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="marketingEmailLogsModalLabel">Marketing Email Logs</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            @include("layouts.table.pagination.from_to",['type' => 'marketingEmailLogs'])
                                            @include("layouts.table.pagination.header",['model' => $marketingEmailLogs, 'tab' =>'info', 'type' => 'marketingEmailLogs'])
                                            <div class="table-responsive mt-4">
                                                <table class="table align-middle pagination_table mb-0 table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Send Date</th>
                                                            <th>Sent By</th>
                                                            <th>Sent To</th>
                                                            <th>Sent From</th>
                                                            <th>Template</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($marketingEmailLogs as $marketingEmailLog)
                                                            <tr>
                                                                <td>
                                                                    {{ date('d/m/Y H:i', strtotime($marketingEmailLog->created_at)) }}
                                                                </td>
                                                                <th>
                                                                    <a @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show') ) href="{{ route('user.show',$marketingEmailLog->user->id ) }}" @endif >
                                                                        <h6 class="mb-1 font-14">
                                                                            {{$marketingEmailLog->user->first_name}} {{$marketingEmailLog->user->last_name}} ({{$marketingEmailLog->user->username}})
                                                                        </h6>
                                                                    </a>
                                                                </th>
                                                                <td>
                                                                    @if ($marketingEmailLog->client)
                                                                        <a @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show') ) href="{{ route('client.show', $marketingEmailLog->client->id) }}" @endif>
                                                                            <h6 class="mb-1 font-14">
                                                                                {{$marketingEmailLog->client->first_name}} {{$marketingEmailLog->client->last_name}}
                                                                            </h6>
                                                                        </a>
                                                                    @else
                                                                        {{$marketingEmailLog->text}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'sender_email_show') ) href="{{ route('sender_emails.show', $marketingEmailLog->sender_email_id) }}" @endif>
                                                                        <h6 class="mb-1 font-14">
                                                                            {{$marketingEmailLog->sender_email->email}}
                                                                        </h6>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($marketingEmailLog->template_id)
                                                                        <a @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'emails_template_show') ) href="{{ route('emails.show', $marketingEmailLog->template_id) }}" @endif>
                                                                            <h6 class="mb-1 font-14">
                                                                                {{$marketingEmailLog->template?->name}}
                                                                            </h6>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @include("layouts.table.pagination.footer",['model' => $marketingEmailLogs, 'tab' =>'info'])
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'send_emails') )
                <div class="compose-mail-popup">
                    <div class="card">
                        <div class="card-header bg-dark text-white py-2 cursor-pointer">
                            <div class="d-flex align-items-center">
                                <div class="compose-mail-title">New Email</div>
                                <div class="compose-mail-close ms-auto">x</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('emails.send',['client_id' => $client->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="email-form">
                                    <div class="mb-3">
                                        <label for="sender_email_id" class="small">From</label>
                                        <div class="input-group">
                                            <select class="single-select form-select" id="sender_email_id" name="sender_email_id" required>
                                                <option value="">Select Email</option>
                                                @foreach ($senderEmails as $senderEmail)
                                                    <option value="{{$senderEmail->id}}">{{$senderEmail->email}}</option>
                                                @endforeach
                                            </select>
                                            @error('sender_email_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_emails2" class="small">Send To</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="client_emails2" id="client_emails2" value="{{$client->email}}" readonly required>
                                            @error('client_emails2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="template_id" class="small">Template</label>
                                        <div class="input-group">
                                            <select class="single-select form-select" data-col="template" id="template_id" name="template_id">
                                                <option value="">Select Template</option>
                                                @foreach ($emailTemplates as $emailTemplate)
                                                    <option value="{{$emailTemplate->id}}">{{$emailTemplate->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('template_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="template">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="subject" placeholder="Subject"></input>
                                            @error('subject')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            @include("layouts.text_editor",['body' => ''])
                                        </div>
                                        <div class="mb-3">
                                            <label for="attachment" class="form-label">Attachment</label>
                                            <input class="form-control" type="file" id="attachment" name="attachment[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" multiple>
                                            @error('attachment')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="">
                                            <input checked data-col="template-name" type="checkbox" name="save_as_template" id="save_as_template" value="1">
                                            <label for="save_as_template" class="form-label">Save as template</label>
                                            @error('save_as_template')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 template-name">
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Template Name" required>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="d-flex align-items-center">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-primary">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_renew') )
        <div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renewModalLabel">Lead Renew</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to renew this lead?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('client.renew', $client->id) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Renew</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-date-time-pickers.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_edit') )
        <script>
            $('#edit_btn').on('click', function() {
                $('.editable').removeAttr('readonly disabled');
                $('#edit_btn').addClass('d-none');
                $('#cancel_btn, #save_btn').removeClass('d-none');
            });
        </script>
    @endif
    {{-- textEditor --}}
    <script src="{{ url('assets/plugins/external/sample/js/common.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/external/dist/suneditor.min.js?v2.944') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.49.0/lib/codemirror.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.49.0/mode/htmlmixed/htmlmixed.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.49.0/mode/xml/xml.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.49.0/mode/css/css.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.11.1/dist/katex.min.js"></script>
    <script>
        const editorInstance = SUNEDITOR.create('editor_classic', {
            display: 'block',
            width: '100%',
            height: 'auto',
            popupDisplay: 'full',
            charCounter: true,
            charCounterLabel: 'Characters :',
            imageGalleryUrl: 'https://etyswjpn79.execute-api.ap-northeast-1.amazonaws.com/suneditor-demo',
            buttonList: [
                // default
                ['undo', 'redo'],
                ['font', 'fontSize', 'formatBlock'],
                ['paragraphStyle', 'blockquote'],
                ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                ['fontColor', 'hiliteColor', 'textStyle'],
                ['removeFormat'],
                ['outdent', 'indent'],
                ['align', 'horizontalRule', 'list', 'lineHeight'],
                ['table', 'link', 'image', 'video', 'audio', 'math'],
                ['imageGallery'],
                ['fullScreen', 'showBlocks', 'codeView'],
                ['preview', 'print'],
                ['save', 'template'],
                // (min-width: 1546)
                ['%1546', [
                    ['undo', 'redo'],
                    ['font', 'fontSize', 'formatBlock'],
                    ['paragraphStyle', 'blockquote'],
                    ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                    ['fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    ['table', 'link', 'image', 'video', 'audio', 'math'],
                    ['imageGallery'],
                    ['fullScreen', 'showBlocks', 'codeView'],
                    ['-right', ':i-More Misc-default.more_vertical', 'preview', 'print', 'save', 'template']
                ]],
                // (min-width: 1455)
                ['%1455', [
                    ['undo', 'redo'],
                    ['font', 'fontSize', 'formatBlock'],
                    ['paragraphStyle', 'blockquote'],
                    ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                    ['fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    ['table', 'link', 'image', 'video', 'audio', 'math'],
                    ['imageGallery'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
                ]],
                // (min-width: 1326)
                ['%1326', [
                    ['undo', 'redo'],
                    ['font', 'fontSize', 'formatBlock'],
                    ['paragraphStyle', 'blockquote'],
                    ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                    ['fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template'],
                    ['-right', ':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery']
                ]],
                // (min-width: 1123)
                ['%1123', [
                    ['undo', 'redo'],
                    [':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
                    ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                    ['fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template'],
                    ['-right', ':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery']
                ]],
                // (min-width: 817)
                ['%817', [
                    ['undo', 'redo'],
                    [':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
                    ['bold', 'underline', 'italic', 'strike'],
                    [':t-More Text-default.more_text', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template'],
                    ['-right', ':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery']
                ]],
                // (min-width: 673)
                ['%673', [
                    ['undo', 'redo'],
                    [':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
                    [':t-More Text-default.more_text', 'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    [':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
                ]],
                // (min-width: 525)
                ['%525', [
                    ['undo', 'redo'],
                    [':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
                    [':t-More Text-default.more_text', 'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    [':e-More Line-default.more_horizontal', 'align', 'horizontalRule', 'list', 'lineHeight'],
                    [':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
                ]],
                // (min-width: 420)
                ['%420', [
                    ['undo', 'redo'],
                    [':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
                    [':t-More Text-default.more_text', 'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle', 'removeFormat'],
                    [':e-More Line-default.more_horizontal', 'outdent', 'indent', 'align', 'horizontalRule', 'list', 'lineHeight'],
                    [':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery'],
                    ['-right', ':i-More Misc-default.more_vertical', 'fullScreen', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
                ]]
            ],
            placeholder: 'Start typing something...',
            templates: [
                {
                    name: 'Template-1',
                    html: '<p>HTML source1</p>'
                },
                {
                    name: 'Template-2',
                    html: '<p>HTML source2</p>'
                }
            ],
            codeMirror: CodeMirror,
            katex: katex
        });
    </script>
@endsection
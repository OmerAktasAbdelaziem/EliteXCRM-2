@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <style>
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
    </style>
@endsection
@section('title',
    (($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show')) ? $client->first_name : '') . ' ' .
    (($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show')) ? $client->last_name : '')
)
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">                   
            <div class="row">
                <div class="col-12">
                    <div class="card" style="background-color: #0d6efd;margin-bottom:0;border-radius: 0;box-shadow: none !important">
                        <div class="card-body" style="padding-bottom: 5px">
                            <div class="row text-white">
                                <div class="col-md-2 col-6">
                                    <small class="form-label">Name</small>
                                    <h3 class="text-white mb-3">
                                        @if ($client->is_renew)
                                            <i class="text-red bx bx-caret-down h2 mb-0"></i>
                                        @endif
                                        @if($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show'))
                                            {{$client->first_name}}
                                        @elseif(UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_hide'))
                                            {{ substr($client->first_name, 0, ceil(strlen($client->first_name) / 2)) }}******
                                        @else
                                            ******
                                        @endif
                                        @if($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show'))
                                            {{$client->last_name}}
                                        @elseif(UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_hide'))
                                            {{ substr($client->last_name, 0, ceil(strlen($client->last_name) / 2)) }}******
                                        @else
                                            ******
                                        @endif
                                    </h3>
                                    <h6 class="text-warning m-0">
                                        @if ($client->smart_user_id)
                                            Smart Client
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-md-1 col-6">
                                    <small class="form-label">ID</small>
                                    <h4>
                                        <a class="text-white" href="{{ route('client.show', $client->id) }}" target="_blank" rel="noopener noreferrer">{{$client->id}}</a>
                                    </h4>
                                </div>
                                <?php /* @if ($client->smart_user_id && isset($options['leads_smart']))*/ ?>
                                
                                @if ($client->smart_user_id)
                                    <div class="col-md-1 col-6">
                                        <small class="form-label">Smart ID</small>
                                        <h4>
                                            <a class="text-white" href="{{ route('smart.show', $client->id) }}">{{$client->smart_user_id}}</a>
                                        </h4>
                                    </div>
                                @endif
                                
                                @if (($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_main_tp')) && Auth::user()->pipeline->category_id == 1)
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
                <div class="col-12">
                    <div class="row">
                        <div class="col-9 text-end mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#show" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-show font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">More Information</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-3">
                                        <div class="tab-pane fade active show" id="show" role="tabpanel">
                                            @if (Auth::user()->pipeline->category_id == 1)
                                                <div class="row" style="font-size: 15px !important;font-weight: bold !important;">
                                                    <div class="col-md-6">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">يتطلب الاستثمار في الأسواق العالمية مبلغ 300 دولار على الأقل، هل لديك هذا المبلغ؟</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->is_have_money !== null ? ($client->is_have_money == 0 ? 'لا' : ($client->is_have_money == 1 ? 'نعم' : '')) : '' }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">هل لديك ساعة يوميا للعمل على استثمارك؟</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->is_have_time !== null ? ($client->is_have_time == 0 ? 'لا' : ($client->is_have_time == 1 ? 'نعم' : '')) : '' }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">هل سبق لك أن حاولت استثمار أموالك؟</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->is_have_invest !== null ? ($client->is_have_invest == 0 ? 'لا' : ($client->is_have_invest == 1 ? 'نعم' : '')) : '' }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">هل عمرك أكثر من 25؟</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->is_25 !== null ? ($client->is_25 == 0 ? 'لا' : ($client->is_25 == 1 ? 'نعم' : '')) : '' }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">كم تملك من المال للاستثمار</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->how_money }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (Auth::user()->pipeline->category_id == 2)
                                                <div class="row" style="font-size: 15px !important;font-weight: bold !important;">
                                                    <div class="col-md-6">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">Company Name</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->company_name }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">Appointment Date</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {{ $client->appointment_date }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="flex-grow-1">
                                                            <small class="form-label">Message</small>
                                                            <h5>
                                                                <span class="input-group-text bg-transparent" style="justify-content: right">
                                                                    <i class='bx'></i> &nbsp;
                                                                    {!! $client->message !!}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
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
@endsection
@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
	<link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css?v2.944') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v2.944') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v2.944') }}">
    <style>
        .dcalendarpicker .dudp__wrapper {
            top: 24px !important;
            bottom: unset !important;
        }
        li.select2-selection__choice {
            color: black;
        }
        .table>:not(caption)>*>* {
            padding: 0.2rem .3rem !important;
        }
    </style>
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
                        <div class="card w-100" style="border-radius: 0 0 10px 10px">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_all_leads'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == "contacts") active @endif" data-bs-toggle="tab" href="#AllContact" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-user-circle font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">All Leads</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_b2b'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == "broker") active @endif" data-bs-toggle="tab" href="#broker" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-user-circle font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">EliteX - B2Broker</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_my_leads'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == "myContact") active @endif" data-bs-toggle="tab" href="#MyContact" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">My Contacts</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_new'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link position-relative @if ($tab == "new") active @endif" data-bs-toggle="tab" href="#new_lead" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-comment-x font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">New Lead</div>
                                                    @if ($new->total() > 0)
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">@if ($new->total() > 99) +99 @else {{$new->total()}} @endif<span class="visually-hidden">unread messages</span></span>
                                                    @endif
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_actions'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == "actions") active @endif" data-bs-toggle="tab" href="#History" role="tab" id="action_tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-archive font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Actions</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_history'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'history') active @endif" data-bs-toggle="tab" href="#history" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-dollar-circle font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Money History</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_hot'))
                                    
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link position-relative @if ($tab == "hot") active @endif" data-bs-toggle="tab" href="#hot_lead" role="tab" id="hot_lead_tab" aria-selected="true">
                                                <div class="d-flex align-items-center @if ($hot->total() > 0) text-danger @endif">
                                                    <div class="tab-icon"><i class="bx bx-meteor font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Hot Lead</div>
                                                    @if ($hot->total() > 0)
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">@if ($hot->total() > 99) +99 @else {{$hot->total()}} @endif<span class="visually-hidden">unread messages</span></span>
                                                    @endif
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <form id="addemployee" name="addemployee" method="GET">
                                        @csrf
                                    </form>
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_all_leads'))
                                    
                                        <div class="tab-pane fade @if ($tab == "contacts") active show @endif" id="AllContact" role="tabpanel">
                                            @include("layouts.table.leads_table",['model' => $contacts,'check_type' => 'contacts','filters' => $contacts_filters ,'tab' => 'contacts','statuses' => $statuses])
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_b2b'))
                                    
                                        <div class="tab-pane fade @if ($tab == "broker") active show @endif" id="broker" role="tabpanel">
                                            @include("layouts.table.leads_table",['model' => $broker,'check_type' => 'broker','filters' => $broker_filters,'tab' => 'broker','statuses' => $statuses])
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_my_leads'))
                                    
                                        <div class="tab-pane fade @if ($tab == "myContact") active show @endif" id="MyContact" role="tabpanel">
                                            @include("layouts.table.leads_table",['model' => $mycontact,'check_type' => 'mycontact','filters' => $mycontact_filters,'tab' => 'myContact','statuses' => $statuses])
                                        </div>
                                    @endif
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_new'))
                                    
                                        <div class="tab-pane fade @if ($tab == "new") active show @endif" id="new_lead" role="tabpanel">
                                            @include("layouts.table.leads_table",['model' => $new,'check_type' => 'new','filters' => $new_filters,'tab' => 'new','statuses' => $statuses])
                                        </div>
                                    @endif
                                    
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_actions'))
                                    
                                        <div class="tab-pane fade @if ($tab == "actions") active show @endif" id="History" role="tabpanel">
                                            @include("layouts.table.pagination.from_to",['type' => 'actions'])
                                            @include("layouts.table.pagination.header",['model' => $actions, 'tab' =>'actions', 'type' => 'actions'])
                                            <div class="table-responsive mt-4">
                                                <table class="table align-middle pagination_table mb-0 table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Lead</th>
                                                            <th>Lead ID</th>
                                                            <th>Employee</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actions as $action)
                                                            <tr>
                                                                <td>
                                                                    {{ date('d/m/Y H:i', strtotime($action->created_at)) }}
                                                                </td>
                                                                <td>
                                                                    @if (strpos($action->text, '<span class="text-primary">Uploaded') === false && $action->client)
                                                                        <a @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show')) href="{{ route('client.show', $action->client?->id) }}" @endif>
                                                                            <h6 class="mb-1 font-14">
                                                                                {{$action->client?->first_name}} {{$action->client?->last_name}}
                                                                            </h6>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (strpos($action->text, '<span class="text-primary">Uploaded') === false)
                                                                        #{{$action->client?->id}}
                                                                    @endif
                                                                </td>
                                                                <th>
                                                                    <a @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show')) href="{{ route('user.show',$action->user->id ) }}" @endif>
                                                                        <h6 class="mb-1 font-14">
                                                                            {{$action->user->first_name}} {{$action->user->last_name}} ({{$action->user->username}})
                                                                        </h6>
                                                                    </a>
                                                                </th>
                                                                <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                    {!! $action->text !!}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @include("layouts.table.pagination.footer",['model' => $actions, 'tab' =>'actions'])
                                        </div>
                                    @endif
                                    
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_history'))
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
                                                                            <a href="{{ $history->client ? route('client.show', $history->client->id) : '#' }}">
                                                                                <h6 class="mb-1 font-14">
                                                                                    {{ optional($history->client)->first_name }} {{ optional($history->client)->last_name }}
                                                                                </h6>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            #{{ optional($history->client)->id ?? 'N/A' }}
                                                                        </td>
                                                                        <th>
                                                                            @if ($history->user?->id)
                                                                                <a @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show')) href="{{ route('user.show',$history->user->id ) }}" @endif >
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
                                    @endif
                                    
                                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_tabs_hot'))
                                    
                                        <div class="tab-pane fade @if ($tab == "hot") active show @endif" id="hot_lead" role="tabpanel">
                                            @include("layouts.table.leads_table",['model' => $hot,'check_type' => 'hot','filters' => $hot_filters ,'tab' => 'hot','statuses' => $statuses])
                                        </div>
                                    @endif
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
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-date-time-pickers.min.js?v2.944') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    <script>
        document.querySelectorAll('.modal-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var formId = this.getAttribute('form');
                var form = document.getElementById(formId);

                if (form) {
                    form.action = this.getAttribute('formaction');
                }

                var clientId = this.getAttribute('data-client-id');
                const chatContents = document.querySelectorAll('.chat-content');
                const commentNumberDivs = document.querySelectorAll('.comment_number');

                chatContents.forEach(chatContent => {
                    chatContent.innerHTML = '';
                });

                commentNumberDivs.forEach(commentNumberDiv => {
                    commentNumberDiv.innerHTML = '';
                });

                function autoResize(textarea) {
                    textarea.style.height = 'auto';
                    textarea.style.height = (textarea.scrollHeight) + 'px';
                }

                fetch(`/clients/${clientId}/comments/`)
                    .then(response => response.json())
                    .then(data => {
                        updateChatContent(data);
                        updateCommentNumber(data.length);

                        setTimeout(function() {
                            const textareas = document.querySelectorAll('textarea');
                            textareas.forEach(function(textarea) {
                                autoResize(textarea);
                            });
                        }, 500);

                        const textareas = document.querySelectorAll('textarea');
                        textareas.forEach(function(textarea) {
                            autoResize(textarea);
                        });
                    })
                    .catch(error => console.error('Error fetching client comments:', error));
            });
        });

        function updateChatContent(comments) {
            const chatContents = document.querySelectorAll('.chat-content');

            comments.forEach(comment => {
                const commentHTML = `
                    <div class="chat-content-leftside">
                        <div class="d-flex">
                            <div class="flex-grow-1 ms-2">
                                <p class="mb-0 chat-time">${comment.user.first_name} (${comment.user.username}), ${new Date(comment.created_at).toLocaleString()}</p>
                                <textarea class="form-control border-0" name="comment" placeholder="Type Comment..." readonly style="resize: none;cursor: default;">${comment.comment}</textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                `;
                
                chatContents.forEach(chatContent => {
                    chatContent.insertAdjacentHTML('beforeend', commentHTML);
                });
            });
        }

        function updateCommentNumber(count) {
            const commentNumberDivs = document.querySelectorAll('.comment_number');
            commentNumberDivs.forEach(function(commentDiv) {
                commentDiv.innerHTML = `${count} Comments`;
            });
        }

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }
        
        $(document).ready(function() {
            function updateChoices() {
                $('.select2-selection__rendered').each(function() {
                    var choices = $(this).find('.select2-selection__choice');
                    if (choices.length) {
                        choices.hide();
                        choices.last().show();
                    }
                });
            }
            setInterval(updateChoices, 1);
        });
    </script>
@endsection
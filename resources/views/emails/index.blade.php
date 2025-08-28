@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
	<link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
	<link href="{{ url('assets/plugins/input-tags/css/tagsinput.min.css?v2.944') }}" rel="stylesheet" />
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
        .se-wrapper-inner{
            max-height:300px ;
        }
        .table-responsive {
            position: relative;
            max-height: 700px;
            overflow-y: auto;
        }
        .scrollable_table thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #f8f9fa;
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
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">Email Templates</h5>
                                </div>
                                <div class="font-22 ms-auto">
                                    @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'emails_sender_email_list') )
                                    
                                        <a href="{{ route('sender_emails.index') }}" class="btn btn-danger btn-sm">
                                            Sender Emails
                                        </a>
                                    @endif
                                    @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'emails_template_create') )
                                    
                                        <a href="{{ route('emails.create') }}" class="btn btn-primary btn-sm">
                                            Add new Template
                                        </a>
                                    @endif
                                    @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'send_emails') )
                                    
                                        <a href="javascript:;" class="btn btn-success btn-sm compose-mail-btn">
                                            Send Email
                                        </a>
                                    @endif
                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#marketingEmailLogsModal">
                                        Marketing Email Logs
                                    </button>
                                </div>
                            </div>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                    <div class="table-responsive mt-4">
                                        <table class="table align-middle mb-0 table-hover data-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Tempate Name</th>
                                                    <th>Subject</th>
                                                    <th>Content</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($emailTemplates as $emailTemplate)
                                                    <tr>
                                                        <td><a @($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'emails_template_show') ) href="{{ route('emails.show', $emailTemplate->id) }}" @endisset>{{$emailTemplate->name}}</a></td>
                                                        <td>{{$emailTemplate->subject}}</td>
                                                        <td>{{ \Illuminate\Support\Str::limit($emailTemplate->body, 40) }}</td>
                                                        <td>{{date('d/m/Y H:i', strtotime($emailTemplate->created_at))}}</td>
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
            @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'send_emails') )
            
                <div class="compose-mail-popup">
                    <div class="card">
                        <div class="card-header bg-dark text-white py-2 cursor-pointer">
                            <div class="d-flex align-items-center">
                                <div class="compose-mail-title">New Email</div>
                                <div class="compose-mail-close ms-auto">x</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('emails.send') }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="client_emails" class="small">Send To</label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#leadsModal">
                                                <span class="number">0</span> Selected
                                            </button>
                                            <input type="hidden" id="client_emails" name="client_emails">
                                            @error('client_emails')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_emails2" class="small">Send To Emails</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control visually-hidden" name="client_emails2" id="client_emails2" data-role="tagsinput">
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
        
    <div class="modal fade" id="leadsModal" tabindex="-1" aria-labelledby="leadsModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 2500px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadsModalLabel">Select Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive mt-4">
                    <table class="table align-middle scrollable_table mb-0 table-hover filterable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">
                                    <input class="form-check-input me-3 check-all-table" data-target="check-contact" type="checkbox">Name
                                </th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 20%">Phone</th>
                                <th style="width: 20%">Country</th>
                                <th style="width: 20%">Status</th>
                            </tr>
                            <tr>
                                <th style="width: 20%">
                                    <input class="form-control" type="text" id="filter_name" placeholder="Filter by Name">
                                </th>
                                <th style="width: 20%">
                                    <input class="form-control" type="text" id="filter_email" placeholder="Filter by Email">
                                </th>
                                <th style="width: 20%">
                                    <input class="form-control" type="text" id="filter_phone" placeholder="Filter by Phone">
                                </th>
                                <th style="width: 20%">
                                    <div class="input-group">
                                        <select class="form-select multiple-select flag_country" id="filter_country" multiple>
                                            <option value="IQ" data-flag="https://flagcdn.com/w320/iq.png">IQ</option>
                                            <option value="LY" data-flag="https://flagcdn.com/w320/ly.png">LY</option>
                                            <option value="EG" data-flag="https://flagcdn.com/w320/eg.png">EG</option>
                                            <option value="SD" data-flag="https://flagcdn.com/w320/sd.png">SD</option>
                                            <option value="JO" data-flag="https://flagcdn.com/w320/jo.png">JO</option>
                                            <option value="SY" data-flag="https://flagcdn.com/w320/sy.png">SY</option>
                                            <option value="SA" data-flag="https://flagcdn.com/w320/sa.png">SA</option>
                                            <option value="LB" data-flag="https://flagcdn.com/w320/lb.png">LB</option>
                                            <option value="MA" data-flag="https://flagcdn.com/w320/ma.png">MA</option>
                                            <option value="TN" data-flag="https://flagcdn.com/w320/tn.png">TN</option>
                                            <option value="KW" data-flag="https://flagcdn.com/w320/kw.png">KW</option>
                                            <option value="DZ" data-flag="https://flagcdn.com/w320/dz.png">DZ</option>
                                            <option value="OM" data-flag="https://flagcdn.com/w320/om.png">OM</option>
                                            <option value="QA" data-flag="https://flagcdn.com/w320/qa.png">QA</option>
                                            <option value="BH" data-flag="https://flagcdn.com/w320/bh.png">BH</option>
                                            <option value="PS" data-flag="https://flagcdn.com/w320/ps.png">PS</option>
                                            <option value="AE" data-flag="https://flagcdn.com/w320/ae.png">AE</option>
                                        </select>
                                    </div>
                                </th>
                                <th style="width: 20%">
                                    <select class="multiple-select form-select" id="filter_status" multiple>
                                        <option value="">Select Status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{$status->name}}">{{$status->name}}</option>
                                        @endforeach
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $contact)
                                @php
                                    $countryMap = [
                                        'Iraq' => 'IQ',
                                        'IQ' => 'IQ',
                                        'عراق' => 'IQ',
                                        'العراق' => 'IQ',
                                        'Libya' => 'LY',
                                        'LY' => 'LY',
                                        'ليبيا' => 'LY',
                                        'Egypt' => 'EG',
                                        'مصر' => 'EG',
                                        'Sudan' => 'SD',
                                        'سودان' => 'SD',
                                        'السودان' => 'SD',
                                        'Jordan' => 'JO',
                                        'اردن' => 'JO',
                                        'الاردن' => 'JO',
                                        'Syria' => 'SY',
                                        'سوريا' => 'SY',
                                        'Saudi Arabia' => 'SA',
                                        'السعودية' => 'SA',
                                        'Lebanon' => 'LB',
                                        'لبنان' => 'LB',
                                        'Morocco' => 'MA',
                                        'مغرب' => 'MA',
                                        'المغرب' => 'MA',
                                        'Tunisia' => 'TN',
                                        'تونس' => 'TN',
                                        'Kuwait' => 'KW',
                                        'كويت' => 'KW',
                                        'الكويت' => 'KW',
                                        'Algeria' => 'DZ',
                                        'جزائر' => 'DZ',
                                        'الجزائر' => 'DZ',
                                        'Oman' => 'OM',
                                        'عمان' => 'OM',
                                        'Qatar' => 'QA',
                                        'قطر' => 'QA',
                                        'Bahrain' => 'BH',
                                        'بحرين' => 'BH',
                                        'البحرين' => 'BH',
                                        'Palestine' => 'PS',
                                        'فلسطين' => 'PS',
                                        'United Arab Emirates' => 'AE',
                                        'امارات' => 'AE',
                                        'الامارات' => 'AE',
                                    ];
                                    
                                    $countryCode = isset($countryMap[$contact->country??'']) ? $countryMap[$contact->country??''] : $contact->country;
                                @endphp

                                <tr data-name="{{$contact->first_name}} {{$contact->last_name}}"
                                    data-email="{{$contact->email}}"
                                    data-phone="{{$contact->phone1}} {{ $contact->phone2 }}"
                                    data-country="{{$countryCode}}"
                                    data-status="{{$contact->sales_status}}">
                                    <td style="width: 20%">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <input class="form-check-input me-3 check-contact check-number" type="checkbox" value="{{$contact->email}}" aria-label="...">
                                            </div>
                                            <a @if($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show') ) href="{{ route('client.show', ['client' => $contact->id , 'status' => $contact->sales_status]) }}" @endif rel="noopener noreferrer">
                                                {{$contact->first_name}} {{$contact->last_name}}
                                            </a>
                                        </div>
                                    </td>
                                    <td style="width: 20%">{{$contact->email}}</td>
                                    <td style="width: 20%">{{$contact->phone1}}<br>{{ $contact->phone2 }}</td>
                                    <td style="width: 20%">{{$contact->country}}</td>
                                    <td style="width: 20%">{{$contact->sales_status}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    Select <span class="number">0</span> Next
                </button>
            </div>
            </div>
        </div>
    </div>

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
@endsection

@section("script")
    <script src="{{ url('assets/plugins/input-tags/js/tagsinput.js?v2.944') }}"></script>
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

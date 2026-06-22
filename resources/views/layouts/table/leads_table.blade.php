@include("layouts.table.show",['model' => $model, 'check_type' => $check_type, 'statuses' => $statuses])
<form id="filter_form-{{$check_type}}" method="Get">
</form>
<div class="table-responsive mt-4">
    <table class="table align-middle pagination_table mb-0 table-hover">
        <thead class="table-light">
            <tr>
                <th>
                    <input class="form-check-input me-3 check-all-table" data-target="check-{{$check_type}}" type="checkbox">
                 
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_lead_id_show'))
                        Lead ID
                    @endif
                </th>
                <th>Enabled</th>
                
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_show'))
                    <th onclick="sortTableByVar('first_name')" style="cursor: pointer">
                        Name
                        @if (request('sort') == 'first_name')
                            @if (request('order') == 'asc')
                                <span class="text-primary bx bx-caret-up-square"></span>
                            @else
                                <span class="text-primary bx bx-caret-down-square"></span>
                            @endif
                        @endif
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_show') )
                
                    <th style="min-width: 100px;" class="max-w-160">Country</th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_show') )
                
                    <th>Email</th>
                @endif 
                
                 @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_show') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_secondary_phone_show'))
                    <th>Phone Number</th>
                
                    @endif
               
                
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_account_type_show') )
                    <th>Account type</th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_show') )
                    <th>Assigned User</th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_show') )
                
                    <th style="min-width: 150px">Status</th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_date_show') )
                
                    <th style="min-width: 200px;cursor: pointer" onclick="sortTableByVar('ftd_date')">
                        FTD Date
                        @if (request('sort') == 'ftd_date')
                            @if (request('order') == 'asc')
                                <span class="text-primary bx bx-caret-up-square"></span>
                            @else
                                <span class="text-primary bx bx-caret-down-square"></span>
                            @endif
                        @endif
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_date_show') )
                    <th style="min-width: 200px;">
                        First Comment Date
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_date_show') )
                    <th style="min-width: 200px;">
                        Assigned Date
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_modified_date_show') )
                
                    <th style="min-width: 200px;">
                        Modified Date
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_registration_date_show') )
                
                    <th style="min-width: 200px;">
                        Registration Date
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_create_date_show') )
                
                    <th style="min-width: 200px; cursor: pointer;" onclick="sortTableByVar('created_at')">
                        Created At 
                        @if (request('sort') == 'created_at' || !request('sort'))
                            @if (request('order') == 'asc')
                                <span class="text-primary bx bx-caret-up-square"></span>
                            @else
                                <span class="text-primary bx bx-caret-down-square"></span>
                            @endif
                        @endif
                    </th>
                @endif
                <th></th>
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_source_show') )
                
                    <th class="max-w-160">Source</th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_team_show') )
                    <th onclick="sortTableByVar('team')" style="cursor: pointer">
                        Teams
                        @if (request('sort') == 'team')
                            @if (request('order') == 'asc')
                                <span class="text-primary bx bx-caret-up-square"></span>
                            @else
                                <span class="text-primary bx bx-caret-down-square"></span>
                            @endif
                        @endif
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_created_by_show') )
                    <th>Created by</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>
                    <button type="submit" style="display: none">Search</button>
                    <input type="hidden" name="tab" value="{{$tab}}" form="filter_form-{{$check_type}}">
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_lead_id_show') )
                        <input type="text" class="form-control" name="{{$check_type}}_filters[id]" value="{{$filters?$filters['id']:''}}" placeholder="ID" form="filter_form-{{$check_type}}" />
                    @endif
                </th>
                <th>
                    <div class="">
                        <div class="input-group">
                            <select class="single-select filter-select form-select" name="{{$check_type}}_filters[enabled]" form="filter_form-{{$check_type}}">
                                <option value=""  @if (!isset($filters['enabled']) || $filters['enabled'] == '') selected @endif>All</option>
                                <option value="Active" @if (isset($filters['enabled']) && $filters['enabled'] == 'Active') selected @endif>Active</option>
                                <option value="Inactive" @if (isset($filters['enabled']) && $filters['enabled'] == 'Inactive') selected @endif>Inactive</option>
                            </select>
                        </div>
                    </div>
                </th>
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_show')|| UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_hide') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_hide'))
                
                    <th>
                        <input type="text" class="form-control" name="{{$check_type}}_filters[name]" value="{{$filters?$filters['name']:''}}" placeholder="Name" form="filter_form-{{$check_type}}" />
                    </th>
                    @else
                    <th></th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_show') )
                
                    <th class="max-w-160">
                        <div class="input-group">
                            <select class="form-select multiple-select flag_country" name="{{$check_type}}_filters[country][]" form="filter_form-{{$check_type}}" multiple>
                                {{-- <option value="except" data-flag="https://flagcdn.com/w320/cn.png"                      @if (isset($filters['country']) && in_array('except', $filters['country']) ) selected @endif>Except</option>
                                <option value="Iraq,IQ,عراق,العراق" data-flag="https://flagcdn.com/w320/iq.png"        @if (isset($filters['country']) && in_array('Iraq,IQ,عراق,العراق', $filters['country']) ) selected @endif>IQ</option>
                                <option value="Libya,LY,ليبيا" data-flag="https://flagcdn.com/w320/ly.png"             @if (isset($filters['country']) && in_array("Libya,LY,ليبيا", $filters['country']) ) selected @endif>LY</option>
                                <option value="Egypt,مصر" data-flag="https://flagcdn.com/w320/eg.png"                   @if (isset($filters['country']) && in_array("Egypt,مصر", $filters['country']) ) selected @endif>EG</option>
                                <option value="Sudan,سودان,السودان" data-flag="https://flagcdn.com/w320/sd.png"        @if (isset($filters['country']) && in_array("Sudan,سودان,السودان", $filters['country']) ) selected @endif>SD</option>
                                <option value="Jordan,اردن,الاردن" data-flag="https://flagcdn.com/w320/jo.png"          @if (isset($filters['country']) && in_array("Jordan,اردن,الاردن", $filters['country']) ) selected @endif>JO</option>
                                <option value="Syria,سوريا" data-flag="https://flagcdn.com/w320/sy.png"                @if (isset($filters['country']) && in_array("Syria,سوريا", $filters['country']) ) selected @endif>SY</option>
                                <option value="Saudi Arabia,السعودية" data-flag="https://flagcdn.com/w320/sa.png"      @if (isset($filters['country']) && in_array("Saudi Arabia,السعودية", $filters['country']) ) selected @endif>SA</option>
                                <option value="Lebanon,لبنان" data-flag="https://flagcdn.com/w320/lb.png"              @if (isset($filters['country']) && in_array("Lebanon,لبنان", $filters['country']) ) selected @endif>LB</option>
                                <option value="Morocco,مغرب,المغرب" data-flag="https://flagcdn.com/w320/ma.png"       @if (isset($filters['country']) && in_array("Morocco,مغرب,المغرب", $filters['country']) ) selected @endif>MA</option>
                                <option value="Tunisia,تونس" data-flag="https://flagcdn.com/w320/tn.png"               @if (isset($filters['country']) && in_array("Tunisia,تونس", $filters['country']) ) selected @endif>TN</option>
                                <option value="Kuwait,كويت,الكويت" data-flag="https://flagcdn.com/w320/kw.png"        @if (isset($filters['country']) && in_array("Kuwait,كويت,الكويت", $filters['country']) ) selected @endif>KW</option>
                                <option value="Algeria,جزائر,الجزائر" data-flag="https://flagcdn.com/w320/dz.png"     @if (isset($filters['country']) && in_array("Algeria,جزائر,الجزائر", $filters['country']) ) selected @endif>DZ</option>
                                <option value="Oman,عمان" data-flag="https://flagcdn.com/w320/om.png"                 @if (isset($filters['country']) && in_array("Oman,عمان", $filters['country']) ) selected @endif>OM</option>
                                <option value="Qatar,قطر" data-flag="https://flagcdn.com/w320/qa.png"                 @if (isset($filters['country']) && in_array("Qatar,قطر", $filters['country']) ) selected @endif>QA</option>
                                <option value="Bahrain,بحرين,البحرين" data-flag="https://flagcdn.com/w320/bh.png"    @if (isset($filters['country']) && in_array("Bahrain,بحرين,البحرين", $filters['country']) ) selected @endif>BH</option>
                                <option value="Palestine,فلسطين" data-flag="https://flagcdn.com/w320/ps.png"          @if (isset($filters['country']) && in_array("Palestine,فلسطين", $filters['country']) ) selected @endif>PS</option>
                                <option value="United Arab Emirates,امارات,الامارات" data-flag="https://flagcdn.com/w320/ae.png" @if (isset($filters['country']) && in_array("United Arab Emirates,امارات,الامارات", $filters['country']) ) selected @endif>AE</option> --}}

                                @if(isset($countries))
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}" data-flag="https://flagcdn.com/w320/{{ $country }}.png" @if (isset($filters['country']) && in_array($country, $filters['country']) ) selected @endif >{{ config("countries.$country") }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_show')|| UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_email_hide') )
                
                    <th>
                        <input type="mail" class="form-control" name="{{$check_type}}_filters[mail]" value="{{$filters?$filters['mail']:''}}" placeholder="Email" form="filter_form-{{$check_type}}" />
                    </th>
                    @else
                    <th></th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_show')|| UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_secondary_phone_show')|| UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_primary_phone_hide')|| UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_secondary_phone_hide') )
                    <th>
                        <input type="text" class="form-control" name="{{$check_type}}_filters[phone]" value="{{$filters?$filters['phone']:''}}" placeholder="Phone Number" form="filter_form-{{$check_type}}" />
                    </th>
                    @else
                    <th></th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_account_type_show') )
                
                    <th>
                        <div class="input-group">
                            <select class="single-select filter-select form-select" name="{{$check_type}}_filters[type]" form="filter_form-{{$check_type}}">
                                <option value="" selected>Select Type</option>
                                    <option value="real" @if (isset($filters['type']) && $filters['type'] == 'real') selected @endif>Real</option>
                                    <option value="demo" @if (isset($filters['type']) && $filters['type'] == 'demo') selected @endif>Demo</option>
                            </select>
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_show') )
                
                    <th class="max-w-160">
                        <div class="input-group">
                            <select class="form-select multiple-select" name="{{$check_type}}_filters[user][]" form="filter_form-{{$check_type}}" multiple>
                                <option value="except" @if (isset($filters['user']) && in_array('except', $filters['user']) ) selected @endif>Except</option>
                                @if($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads'))
                                    <option value="unassigned" @if (isset($filters['user']) && in_array('unassigned', $filters['user']) ) selected @endif>Unassigned</option>
                                @endif
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" @if (isset($filters['user']) && in_array($user->id, $filters['user']) ) selected @endif>{{$user->username}}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_show') )
                
                    <th class="max-w-160">
                        <div class="input-group">
                            <select class="form-select multiple-select" name="{{$check_type}}_filters[status][]" form="filter_form-{{$check_type}}" multiple>
                                <option value="except" @if (isset($filters['status']) && in_array('except', $filters['status']) ) selected @endif>Except</option>
                                @foreach ($statuses as $status)
                                    <option value="{{$status->name}}" @if (isset($filters['status']) && in_array($status->name, $filters['status']) ) selected @endif>{{$status->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_date_show') )
                
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control from-to-range" form="filter_form-{{$check_type}}" id="{{$check_type}}_ftd_fromTo" placeholder="{{$filters?$filters['ftd_fromTo']:'Select date range'}}">
                            <input type="hidden" class="rangeDate" form="filter_form-{{$check_type}}" value="{{$filters?$filters['ftd_fromTo']:''}}" name="{{$check_type}}_filters[ftd_fromTo]">
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_date_show') )
                
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control from-to-range" form="filter_form-{{$check_type}}" id="{{$check_type}}_first_comment_at_fromTo" placeholder="{{$filters?$filters['first_comment_at_fromTo']:'Select date range'}}">
                            <input type="hidden" class="rangeDate" form="filter_form-{{$check_type}}" value="{{$filters?$filters['first_comment_at_fromTo']:''}}" name="{{$check_type}}_filters[first_comment_at_fromTo]">
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_date_show') )
                
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control from-to-range" form="filter_form-{{$check_type}}" id="{{$check_type}}_assigned_at_fromTo" placeholder="{{$filters?$filters['assigned_at_fromTo']:'Select date range'}}">
                            <input type="hidden" class="rangeDate" form="filter_form-{{$check_type}}" value="{{$filters?$filters['assigned_at_fromTo']:''}}" name="{{$check_type}}_filters[assigned_at_fromTo]">
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_modified_date_show') )
                
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control from-to-range" form="filter_form-{{$check_type}}" id="{{$check_type}}_modified_at_fromTo" placeholder="{{$filters?$filters['modified_at_fromTo']:'Select date range'}}">
                            <input type="hidden" class="rangeDate" form="filter_form-{{$check_type}}" value="{{$filters?$filters['modified_at_fromTo']:''}}" name="{{$check_type}}_filters[modified_at_fromTo]">
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_registration_date_show') )
                
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control from-to-range" form="filter_form-{{$check_type}}" id="{{$check_type}}_reg_at_fromTo" placeholder="{{$filters?$filters['reg_at_fromTo']:'Select date range'}}">
                            <input type="hidden" class="rangeDate" form="filter_form-{{$check_type}}" value="{{$filters?$filters['reg_at_fromTo']:''}}" name="{{$check_type}}_filters[reg_at_fromTo]">
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_create_date_show') )
                
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control from-to-range" form="filter_form-{{$check_type}}" id="{{$check_type}}_created_fromTo" placeholder="{{$filters?$filters['created_fromTo']:'Select date range'}}">
                            <input type="hidden" class="rangeDate" form="filter_form-{{$check_type}}" value="{{$filters?$filters['created_fromTo']:''}}" name="{{$check_type}}_filters[created_fromTo]">
                        </div>
                    </th>
                @endif
                <th>
                    <button type="submit" form="filter_form-{{$check_type}}" formaction="{{ route('client.index') }}" class="btn btn-sm text-success text-center w-auto">
                        Filter
                    </button>
                </th>
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_source_show') )
                
                    <th class="max-w-160">
                        <div class="input-group">
                            <select class="form-select multiple-select" name="{{$check_type}}_filters[source][]" form="filter_form-{{$check_type}}" multiple>
                                <option value="except" @if (isset($filters['source']) && in_array('except', $filters['source']) ) selected @endif>Except</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source }}" @if (isset($filters['source']) && in_array($source, $filters['source']) ) selected @endif>{{$source}}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_team_show') )
                
                    <th>
                        <div class="input-group">
                            <select class="single-select filter-select form-select" name="{{$check_type}}_filters[teams]" form="filter_form-{{$check_type}}">
                                <option value="" selected>Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{$team->id}}" @if (isset($filters['teams']) && $filters['teams'] == $team->id) selected @endif>{{$team->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                @endif
                @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_created_by_show') )
                
                    <th>
                        <div class="input-group">
                            <select class="form-select multiple-select" name="{{$check_type}}_filters[created_by][]" form="filter_form-{{$check_type}}" multiple>
                                <option value="except" @if (isset($filters['created_by']) && in_array('except', $filters['created_by']) ) selected @endif>Except</option>
                                @foreach ($created_by_users as $created_by_user)
                                    <option value="{{$created_by_user}}" @if (isset($filters['created_by']) && in_array($created_by_user, $filters['created_by']) ) selected @endif>{{$created_by_user}}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                @endif
            </tr>
            @foreach ($model as $contact)
                <tr style="@if($contact->broker_id && !isset($contact->options['isEnabled']))background-color: #f76b79; @endif">
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <input class="form-check-input me-3 check-{{$check_type}} check-number" type="checkbox" form="addemployee" name="clientid[]" value="{{$contact->id}}" aria-label="...">
                            </div>
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_lead_id_show') )
                                <a @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show') ) href="{{ route('client.show', ['client' => $contact->id , 'status' => $contact->sales_status]) }}" @endif rel="noopener noreferrer">
                                    #{{$contact->id}}
                                </a>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center">
                        <input class="form-check-input me-3 is_ftd"
                        @if ($contact->broker_id && $contact->account_type == "Real")
                            checked
                        @endif
                        type="checkbox" aria-label="..." disabled>
                    </td>
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_show'))
                        <td style="width: fit-content">
                            <div class="ms-2">
                                <a @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show') ) href="{{ route('client.show', ['client' => $contact->id , 'status' => $contact->sales_status]) }}" @endif rel="noopener noreferrer">
                                    <h6 class="mb-1 font-14">
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_name_show') )
                                            {{$contact->first_name}}
                                        @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_hide') )
                                            <div>
                                                {{ substr($contact->first_name, 0, ceil(strlen($contact->first_name) / 2)) }}******
                                            </div>
                                        @else
                                            <div>
                                                ******
                                            </div>
                                        @endif
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_last_name_show') )
                                            {{$contact->last_name}}
                                        @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_hide') )
                                            <div>
                                                {{ substr($contact->last_name, 0, ceil(strlen($contact->last_name) / 2)) }}******
                                            </div>
                                        @else
                                            <div>
                                                ******
                                            </div>
                                        @endif
                                    </h6></a>
                            </div>
                        </td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_country_show') )
                        <td>{{ config("countries.$contact->country") }}</td>
                    @endif
                    @if ($contact->email)
                        
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_email_show') )
                                <td title="{{ $contact->email }}" style="max-width: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $contact->email }}
                                </td>
                            @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_email_hide') )
                                <td style="max-width: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ********{{ substr($contact->email, 8) }}
                                </td>
                            @else
                                <td style="max-width: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ******
                                </td>
                            @endif
                        
                    @else
                        <td></td>
                    @endif
                    
                        <td>
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_primary_phone_show') )
                                {{$contact->phone1}}
                            @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_primary_phone_hide') )
                                {{ substr($contact->phone1, 0, ceil(strlen($contact->phone1) / 2)) }}******
                            @else
                                ******
                            @endif
                            <br>
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_secondary_phone_show') )
                                {{$contact->phone2}}
                            @elseif (UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_secondary_phone_show') )
                                {{ substr($contact->phone2, 0, ceil(strlen($contact->phone2) / 2)) }}******
                            @else
                                ******
                            @endif
                        </td>
                  
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_account_type_show') )
                        <td>{{$contact->account_type}}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_user_show') )
                        <td>
                            @if ($contact->user_id != null)
                                <a @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show') ) href="{{ route('user.show',$contact->user->id ) }}" @endif rel="noopener noreferrer">
                                    {{$contact->user->first_name}} {{$contact->user->last_name}}
                                    <p class="mb-0 font-13 text-secondary">{{$contact->user->username}}</p>
                                </a>
                            @endif
                        </td>
                    @endif
                    {{dd($statuses)}}
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_show') )
                        <td>
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit') )
                                <form id="status_form-{{$check_type}}-{{$contact->id}}" method="POST" action="{{ route('client.editStatus', $contact->id) }}">
                                    @csrf
                                    @method('PUT')
                            @endif
                                <select class="single-select form-select filter-select" @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit') ) name="sales_status" form="status_form-{{$check_type}}-{{$contact->id}}" @else disabled @endif>
                                    <option value="" selected >Select Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{$status->name}}" @if ($contact->sales_status == $status->name) selected @endif>{{$status->name}}</option>
                                    @endforeach
                                </select>
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_sales_status_edit') )
                                </form>
                            @endif
                        </td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_ftd_date_show') )
                        <td>{{ $contact->ftd_date?date('d/m/Y H:i', strtotime($contact->ftd_date)):'-' }}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_first_comment_date_show') )
                        <td>{{ $contact->comments->count()>0?date('d/m/Y H:i', strtotime($contact->comments->first()->created_at)):'-' }}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_assigned_date_show') )
                        <td>{{ $contact->assigned_at?date('d/m/Y H:i', strtotime($contact->assigned_at)):'-' }}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_modified_date_show') )
                        <td>{{ $contact->updated_at?date('d/m/Y H:i', strtotime($contact->updated_at)):'-' }}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_registration_date_show') )
                        <td>{{ $contact->reg_date?date('d/m/Y H:i', strtotime($contact->reg_date)):'-' }}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_create_date_show') )
                        <td>{{ date('d/m/Y H:i', strtotime($contact->created_at)) }}</td>
                    @endif
                    <td class="text-center">
                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_cards_comments') )
                            <button type="button" data-client-id="{{ $contact->id }}" form="comment_form-{{$check_type}}" formaction="{{ route('client-comments.store',$contact->id) }}" title="{{$contact->comments->count()>0?strip_tags($contact->comments->last()->comment):''}}" class="btn btn-sm text-primary text-center w-auto modal-btn" data-bs-toggle="modal" data-bs-target="#commentModal-{{$check_type}}" style="background-color: transparent">
                                <i class="bx bx-comment"></i>
                            </button>
                        @endif
                    </td>
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_source_show') )
                        <td style="max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{$contact->source}}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_team_show') )
                        <td>{{$contact->user?->team?->name}}</td>
                    @endif
                    @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'field_created_by_show') )
                        <td>{{$contact->created_by}}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include("layouts.table.footer",['model' => $model, 'check_type' => $check_type])

<div class="modal fade" id="commentModal-{{$check_type}}" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body comment-container">
                <form id="comment_form-{{$check_type}}" method="POST">
                    @csrf
                    <input type="hidden" name="from_index" value="1">
                    <textarea class="comment form-control d-none" name="comment" placeholder="Type Comment..." rows="3"></textarea>
                    @error('comment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="row mt-2">
                        <div class="col comment_number">
                            0 Comments
                        </div>
                        <div class="col justify-content-end d-flex">
                            @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_add_comments'))
                                <button disabled type="submit" class="submit_comment btn my-0 px-0 d-none" style="background-color: transparent"><i class="text-success bx bx-check h6 mb-0"></i></button>
                                <button type="button" class="plus_comment btn my-0 px-0" style="background-color: transparent"><i class="text-primary bx bx-plus h6 mb-0"></i></button>
                                <button type="button" class="x_comment btn my-0 px-0 d-none" style="background-color: transparent"><i class="text-danger bx bx-x h6 mb-0"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
                <hr class="my-0" />
                <div class="chat-content m-0 ps ps--active-y" style="padding:15px;">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function sortTableByVar(sortColumn) {
        let url = new URL(window.location.href);

        if (url.searchParams.get('sort') === sortColumn) {
            let currentOrder = url.searchParams.get('order');
            if (currentOrder === 'desc') {
                url.searchParams.set('order', 'asc');
            } else {
                url.searchParams.set('order', 'desc');
            }
        } else {
            url.searchParams.set('sort', sortColumn);
            url.searchParams.set('order', 'asc');
        }

        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.submit_comment').removeAttribute('disabled');
    });
</script>
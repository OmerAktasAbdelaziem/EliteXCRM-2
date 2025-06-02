<div class="col-md-4">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[leads_create]" value="1" @if (isset($role->options['leads_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" data-col="leads_table" name="options[leads_list]" value="1" @if (isset($role->options['leads_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" data-col="leads" name="options[leads_show]" value="1" @if (isset($role->options['leads_show'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[leads_delete]" value="1" @if (isset($role->options['leads_delete'])) checked @endif />
            </div>
            <div class="col">
                MainTP(Real&Demo)&nbsp;<input type="checkbox" data-col="main_tp" name="options[leads_main_tp]" value="1" @if (isset($role->options['leads_main_tp'])) checked @endif />
            </div>
            <div class="col">
                MainTP(Demo)&nbsp;<input type="checkbox" data-col="main_tp" name="options[leads_main_tp_demo]" value="1" @if (isset($role->options['leads_main_tp_demo'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Retention</label>
        <div class="row">
            <div class="col">
                <input type="checkbox" name="options[retention]" value="1" @if (isset($role->options['retention'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Requests</label>
        <div class="row">
            <div class="col">
                <input type="checkbox" name="options[requests]" value="1" @if (isset($role->options['requests'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Reports</label>
        <div class="row">
            <div class="col">
                <input type="checkbox" name="options[reports_list]" value="1" @if (isset($role->options['reports_list'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Overview</label>
        <div class="row">
            <div class="col">
                <input type="checkbox" name="options[overview]" value="1" @if (isset($role->options['overview'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Users</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[users_create]" value="1" @if (isset($role->options['users_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[users_list]" value="1" @if (isset($role->options['users_list'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[users_delete]" value="1" @if (isset($role->options['users_delete'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[users_show]" value="1" @if (isset($role->options['users_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[users_update]" value="1" @if (isset($role->options['users_update'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Parts</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[parts_create]" value="1" @if (isset($role->options['parts_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[parts_list]" value="1" @if (isset($role->options['parts_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[parts_show]" value="1" @if (isset($role->options['parts_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[parts_update]" value="1" @if (isset($role->options['parts_update'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Teams</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[teams_create]" value="1" @if (isset($role->options['teams_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[teams_list]" value="1" @if (isset($role->options['teams_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[teams_show]" value="1" @if (isset($role->options['teams_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[teams_update]" value="1" @if (isset($role->options['teams_update'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Status</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[status_create]" value="1" @if (isset($role->options['status_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[status_list]" value="1" @if (isset($role->options['status_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[status_show]" value="1" @if (isset($role->options['status_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[status_update]" value="1" @if (isset($role->options['status_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[status_delete]" value="1" @if (isset($role->options['status_delete'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Roles</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[roles_create]" value="1" @if (isset($role->options['roles_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[roles_list]" value="1" @if (isset($role->options['roles_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[roles_show]" value="1" @if (isset($role->options['roles_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[roles_update]" value="1" @if (isset($role->options['roles_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[roles_delete]" value="1" @if (isset($role->options['roles_delete'])) checked @endif />
            </div>
        </div>
    </div>
</div>

<div class="col-md-1">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Settings</label>
        <div class="row">
            <div class="col">
                <input type="checkbox" name="options[settings]" value="1" @if (isset($role->options['settings'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Emails Marketing</label>
        <div class="row">
            <div class="col">
                Sender Emails&nbsp;<input type="checkbox" name="options[emails_sender_emails]" data-col="sender_email" value="1" @if (isset($role->options['emails_sender_emails'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[emails_template_list]" value="1" @if (isset($role->options['emails_template_list'])) checked @endif />
            </div>
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[emails_template_create]" value="1" @if (isset($role->options['emails_template_create'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[emails_template_show]" value="1" @if (isset($role->options['emails_template_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[emails_template_update]" value="1" @if (isset($role->options['emails_template_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[emails_template_delete]" value="1" @if (isset($role->options['emails_template_delete'])) checked @endif />
            </div>
            <div class="col">
                Send Email&nbsp;<input type="checkbox" name="options[emails_send]" value="1" @if (isset($role->options['emails_send'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 sender_email @if (isset($role->options['emails_sender_emails'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Sender Emails</label>
        <div class="row">
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[sender_email_list]" value="1" @if (isset($role->options['sender_email_list'])) checked @endif />
            </div>
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[sender_email_create]" value="1" @if (isset($role->options['sender_email_create'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[sender_email_show]" value="1" @if (isset($role->options['sender_email_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[sender_email_update]" value="1" @if (isset($role->options['sender_email_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[sender_email_delete]" value="1" @if (isset($role->options['sender_email_delete'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Bank</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[bank_create]" value="1" @if (isset($role->options['bank_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[bank_list]" value="1" @if (isset($role->options['bank_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[bank_show]" value="1" @if (isset($role->options['bank_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[bank_update]" value="1" @if (isset($role->options['bank_update'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Assets</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[asset_create]" value="1" @if (isset($role->options['asset_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[asset_list]" value="1" @if (isset($role->options['asset_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[asset_show]" value="1" @if (isset($role->options['asset_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[asset_update]" value="1" @if (isset($role->options['asset_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[asset_delete]" value="1" @if (isset($role->options['asset_delete'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Asset Groups</label>
        <div class="row">
            <div class="col">
                Create&nbsp;<input type="checkbox" name="options[assetGroup_create]" value="1" @if (isset($role->options['assetGroup_create'])) checked @endif />
            </div>
            <div class="col">
                List&nbsp;<input type="checkbox" name="options[assetGroup_list]" value="1" @if (isset($role->options['assetGroup_list'])) checked @endif />
            </div>
            <div class="col">
                Show&nbsp;<input type="checkbox" name="options[assetGroup_show]" value="1" @if (isset($role->options['assetGroup_show'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[assetGroup_update]" value="1" @if (isset($role->options['assetGroup_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[assetGroup_delete]" value="1" @if (isset($role->options['assetGroup_delete'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-12 leads_table @if (isset($role->options['leads_list'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Inputs (Table)</label>
        <div class="row">
            <div class="col-2">
                Lead ID&nbsp;<input type="checkbox" name="options[leads_table_lead_id]" value="1" @if (isset($role->options['leads_table_lead_id'])) checked @endif />
            </div>
            <div class="col-2">
                Name&nbsp;<input type="checkbox" name="options[leads_table_name]" value="1" @if (isset($role->options['leads_table_name'])) checked @endif />
            </div>
            <div class="col-2">
                Country&nbsp;<input type="checkbox" name="options[leads_table_country]" value="1" @if (isset($role->options['leads_table_country'])) checked @endif />
            </div>
            <div class="col-2">
                Email&nbsp;<input type="checkbox" name="options[leads_table_email]" value="1" @if (isset($role->options['leads_table_email'])) checked @endif />
            </div>
            <div class="col-2">
                Phone&nbsp;<input type="checkbox" name="options[leads_table_phone]" value="1" @if (isset($role->options['leads_table_phone'])) checked @endif />
            </div>
            <div class="col-2">
                Account type&nbsp;<input type="checkbox" name="options[leads_table_account_type]" value="1" @if (isset($role->options['leads_table_account_type'])) checked @endif />
            </div>
            <div class="col-2">
                Assigned User&nbsp;<input type="checkbox" name="options[leads_table_user_id]" value="1" @if (isset($role->options['leads_table_user_id'])) checked @endif />
            </div>
            <div class="col-2">
                Status&nbsp;<input type="checkbox" name="options[leads_table_status]" value="1" @if (isset($role->options['leads_table_status'])) checked @endif />
            </div>  
            <div class="col-2">
                FTD Date&nbsp;<input type="checkbox" name="options[leads_table_ftd_date]" value="1" @if (isset($role->options['leads_table_ftd_date'])) checked @endif />
            </div>
            <div class="col-2">
                First Comment Date&nbsp;<input type="checkbox" name="options[leads_table_first_comment_at]" value="1" @if (isset($role->options['leads_table_first_comment_at'])) checked @endif />
            </div>
            <div class="col-2">
                Assigned Date&nbsp;<input type="checkbox" name="options[leads_table_assigned_at]" value="1" @if (isset($role->options['leads_table_assigned_at'])) checked @endif />
            </div>
            <div class="col-2">
                Modified Date&nbsp;<input type="checkbox" name="options[leads_table_modified_at]" value="1" @if (isset($role->options['leads_table_modified_at'])) checked @endif />
            </div>
            <div class="col-2">
                Registration Date&nbsp;<input type="checkbox" name="options[leads_table_reg_at]" value="1" @if (isset($role->options['leads_table_reg_at'])) checked @endif />
            </div>
            <div class="col-2">
                Created At&nbsp;<input type="checkbox" name="options[leads_table_created_at]" value="1" @if (isset($role->options['leads_table_created_at'])) checked @endif />
            </div>
            <div class="col-2">
                Source&nbsp;<input type="checkbox" name="options[leads_table_source]" value="1" @if (isset($role->options['leads_table_source'])) checked @endif />
            </div>
            <div class="col-2">
                Teams&nbsp;<input type="checkbox" name="options[leads_table_teams]" value="1" @if (isset($role->options['leads_table_teams'])) checked @endif />
            </div>
            <div class="col-2">
                Created by&nbsp;<input type="checkbox" name="options[leads_table_created_by]" value="1" @if (isset($role->options['leads_table_created_by'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-12 leads_table @if (isset($role->options['leads_list'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Inputs (Tabs)</label>
        <div class="row">
            <div class="col">
                All Leads&nbsp;<input type="checkbox" name="options[leads_tabs_all_leads]" value="1" @if (isset($role->options['leads_tabs_all_leads'])) checked @endif />
            </div>
            <div class="col">
                My Leads&nbsp;<input type="checkbox" name="options[leads_tabs_my_leads]" value="1" @if (isset($role->options['leads_tabs_my_leads'])) checked @endif />
            </div>
            <div class="col">
                EliteX - B2Broker&nbsp;<input type="checkbox" name="options[leads_tabs_b2b]" value="1" @if (isset($role->options['leads_tabs_b2b'])) checked @endif />
            </div>
            <div class="col">
                New Lead&nbsp;<input type="checkbox" name="options[leads_tabs_new]" value="1" @if (isset($role->options['leads_tabs_new'])) checked @endif />
            </div>
            <div class="col">
                Actions&nbsp;<input type="checkbox" name="options[leads_tabs_actions]" value="1" @if (isset($role->options['leads_tabs_actions'])) checked @endif />
            </div>
            <div class="col">
                Money History&nbsp;<input type="checkbox" name="options[leads_tabs_history]" value="1" @if (isset($role->options['leads_tabs_history'])) checked @endif />
            </div>
            <div class="col">
                Hot Lead&nbsp;<input type="checkbox" name="options[leads_tabs_hot]" value="1" @if (isset($role->options['leads_tabs_hot'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-12 leads_table @if (isset($role->options['leads_list'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Data Show</label>
        <div class="row">
            <div class="col">
                Unassigned Leads&nbsp;<input type="checkbox" name="options[leads_data_show_unassigned_leads]" value="1" @if (isset($role->options['leads_data_show_unassigned_leads'])) checked @endif />
            </div>
            <div class="col-10">
                <label class="form-label">Teams</label>
                <select class="form-select multiple-select" name="options[leads_data_show_teams][]" multiple>
                    @php
                        $groupedTeams = $teams->load('part')->groupBy('part_id');
                    @endphp
                
                    @foreach ($groupedTeams as $partId => $group)
                        @php
                            $partName = $group->first()->part->name ?? 'Unknown Part';
                        @endphp
                        <optgroup label="{{ $partName }}">
                            @foreach ($group as $team)
                                <option value="{{ $team->id }}"
                                    @if (isset($role->options['leads_data_show_teams']) && in_array($team->id, $role->options['leads_data_show_teams']))
                                        selected
                                    @endif
                                >{{ $team->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="col-12 leads @if (isset($role->options['leads_show'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Inputs (Update)</label>
        <div class="row">
            <div class="col-2">
                Can Update&nbsp;<input type="checkbox" data-col="update_leads" name="options[leads_can_update]" value="1" @if (isset($role->options['leads_can_update'])) checked @endif />
            </div>
            <div class="col-2">
                Can Renew&nbsp;<input type="checkbox" name="options[leads_can_renew]" value="1" @if (isset($role->options['leads_can_renew'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                First Name&nbsp;<input type="checkbox" name="options[leads_first_name]" value="1" @if (isset($role->options['leads_first_name'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Last Name&nbsp;<input type="checkbox" name="options[leads_last_name]" value="1" @if (isset($role->options['leads_last_name'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Email Address&nbsp;<input type="checkbox" name="options[leads_email]" value="1" @if (isset($role->options['leads_email'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Primary Number&nbsp;<input type="checkbox" name="options[leads_phone1]" value="1" @if (isset($role->options['leads_phone1'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Secondary Number&nbsp;<input type="checkbox" name="options[leads_phone2]" value="1" @if (isset($role->options['leads_phone2'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Sales Status&nbsp;<input type="checkbox" name="options[leads_status]" value="1" @if (isset($role->options['leads_status'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                FTD&nbsp;<input type="checkbox" name="options[leads_ftd]" value="1" @if (isset($role->options['leads_ftd'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Assigned User&nbsp;<input type="checkbox" name="options[leads_user_id]" value="1" @if (isset($role->options['leads_user_id'])) checked @endif />
            </div>  
            <div class="col-2 update_leads">
                Country&nbsp;<input type="checkbox" name="options[leads_country]" value="1" @if (isset($role->options['leads_country'])) checked @endif />
            </div>
            <div class="col-2 update_leads">
                Age&nbsp;<input type="checkbox" name="options[leads_age]" value="1" @if (isset($role->options['leads_age'])) checked @endif />
            </div>
            @if (auth()->user()->pipeline->broker_id == 2 && auth()->user()->pipeline->category_id == 1)
                <div class="col-2">
                    Account Type &nbsp;<input type="checkbox" name="options[leads_account_type]" value="1" @if (isset($role->options['leads_account_type'])) checked @endif />
                </div>
                <div class="col-2">
                    Asset Group &nbsp;<input type="checkbox" name="options[leads_asset_group]" value="1" @if (isset($role->options['leads_asset_group'])) checked @endif />
                </div>
            @endif
        </div>
    </div>
</div>
<div class="col-12 leads @if (isset($role->options['leads_show'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Inputs (Show)</label>
        <div class="row">
            <div class="col-2">
                First Name&nbsp;<input type="checkbox" name="options[leads_show_first_name]" value="1" @if (isset($role->options['leads_show_first_name'])) checked @endif />
                &nbsp;Hide&nbsp;<input type="checkbox" class="hide" name="options[leads_show_first_name_hide]" value="1" @if (isset($role->options['leads_show_first_name_hide'])) checked @endif />
            </div>
            <div class="col-2">
                Last Name&nbsp;<input type="checkbox" name="options[leads_show_last_name]" value="1" @if (isset($role->options['leads_show_last_name'])) checked @endif />
                &nbsp;Hide&nbsp;<input type="checkbox" class="hide" name="options[leads_show_last_name_hide]" value="1" @if (isset($role->options['leads_show_last_name_hide'])) checked @endif />
            </div>
            <div class="col-2">
                Email Address&nbsp;<input type="checkbox" name="options[leads_show_email]" value="1" @if (isset($role->options['leads_show_email'])) checked @endif />
                &nbsp;Hide&nbsp;<input type="checkbox" class="hide" name="options[leads_show_email_hide]" value="1" @if (isset($role->options['leads_show_email_hide'])) checked @endif />
            </div>
            <div class="col-2">
                Primary Number&nbsp;<input type="checkbox" name="options[leads_show_phone1]" value="1" @if (isset($role->options['leads_show_phone1'])) checked @endif />
                &nbsp;Hide&nbsp;<input type="checkbox" class="hide" name="options[leads_show_phone1_hide]" value="1" @if (isset($role->options['leads_show_phone1_hide'])) checked @endif />
            </div>
            <div class="col-2">
                Secondary Number&nbsp;<input type="checkbox" name="options[leads_show_phone2]" value="1" @if (isset($role->options['leads_show_phone2'])) checked @endif />
                &nbsp;Hide&nbsp;<input type="checkbox" class="hide" name="options[leads_show_phone2_hide]" value="1" @if (isset($role->options['leads_show_phone2_hide'])) checked @endif />
            </div>
            <div class="col-2">
                Sales Status&nbsp;<input type="checkbox" name="options[leads_show_status]" value="1" @if (isset($role->options['leads_show_status'])) checked @endif />
            </div>
            <div class="col-2">
                FTD&nbsp;<input type="checkbox" name="options[leads_show_ftd]" value="1" @if (isset($role->options['leads_show_ftd'])) checked @endif />
            </div>
            <div class="col-2">
                Enabled&nbsp;<input type="checkbox" name="options[leads_show_enabled]" value="1" @if (isset($role->options['leads_show_enabled'])) checked @endif />
            </div>
            <div class="col-2">
                Assigned User&nbsp;<input type="checkbox" name="options[leads_show_user_id]" value="1" @if (isset($role->options['leads_show_user_id'])) checked @endif />
            </div>
            <div class="col-2">
                Username&nbsp;<input type="checkbox" name="options[leads_show_username]" value="1" @if (isset($role->options['leads_show_username'])) checked @endif />
            </div>
            <div class="col-2">
                Password&nbsp;<input type="checkbox" name="options[leads_show_pass]" value="1" @if (isset($role->options['leads_show_pass'])) checked @endif />
            </div>
            <div class="col-2">
                USDT&nbsp;<input type="checkbox" name="options[leads_show_usdt]" value="1" @if (isset($role->options['leads_show_usdt'])) checked @endif />
            </div>
            <div class="col-2">
                FTD Amount&nbsp;<input type="checkbox" name="options[leads_show_ftd_amount]" value="1" @if (isset($role->options['leads_show_ftd_amount'])) checked @endif />
            </div>
            <div class="col-2">
                Leverage&nbsp;<input type="checkbox" name="options[leads_show_leverage]" value="1" @if (isset($role->options['leads_show_leverage'])) checked @endif />
            </div>
            <div class="col-2">
                Account Type&nbsp;<input type="checkbox" name="options[leads_show_account_type]" value="1" @if (isset($role->options['leads_show_account_type'])) checked @endif />
            </div>
            <div class="col-2">
                Asset Group&nbsp;<input type="checkbox" name="options[leads_show_asset_group]" value="1" @if (isset($role->options['leads_show_asset_group'])) checked @endif />
            </div>
            <div class="col-2">
                Country&nbsp;<input type="checkbox" name="options[leads_show_country]" value="1" @if (isset($role->options['leads_show_country'])) checked @endif />
            </div>
            <div class="col-2">
                First Owner&nbsp;<input type="checkbox" name="options[leads_show_first_owner]" value="1" @if (isset($role->options['leads_show_first_owner'])) checked @endif />
            </div>
            <div class="col-2">
                Team&nbsp;<input type="checkbox" name="options[leads_show_team]" value="1" @if (isset($role->options['leads_show_team'])) checked @endif />
            </div>
            <div class="col-2">
                Last Deposit Amount&nbsp;<input type="checkbox" name="options[leads_show_lda]" value="1" @if (isset($role->options['leads_show_lda'])) checked @endif />
            </div>
            <div class="col-2">
                First Comment Date&nbsp;<input type="checkbox" name="options[leads_show_first_comment_date]" value="1" @if (isset($role->options['leads_show_first_comment_date'])) checked @endif />
            </div>
            <div class="col-2">
                First Comment Owner&nbsp;<input type="checkbox" name="options[leads_show_first_comment_owner]" value="1" @if (isset($role->options['leads_show_first_comment_owner'])) checked @endif />
            </div>
            <div class="col-2">
                Assigned Date&nbsp;<input type="checkbox" name="options[leads_show_assigned_date]" value="1" @if (isset($role->options['leads_show_assigned_date'])) checked @endif />
            </div>
            <div class="col-2">
                FTD Date&nbsp;<input type="checkbox" name="options[leads_show_ftd_date]" value="1" @if (isset($role->options['leads_show_ftd_date'])) checked @endif />
            </div>
            <div class="col-2">
                Created Date&nbsp;<input type="checkbox" name="options[leads_show_created_date]" value="1" @if (isset($role->options['leads_show_created_date'])) checked @endif />
            </div>
            <div class="col-2">
                Modified Date&nbsp;<input type="checkbox" name="options[leads_show_modified_date]" value="1" @if (isset($role->options['leads_show_modified_date'])) checked @endif />
            </div>
            <div class="col-2">
                Registration Date&nbsp;<input type="checkbox" name="options[leads_show_registration_date]" value="1" @if (isset($role->options['leads_show_registration_date'])) checked @endif />
            </div>
            <div class="col-2">
                Age&nbsp;<input type="checkbox" name="options[leads_show_age]" value="1" @if (isset($role->options['leads_show_age'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-12 main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">MainTp Inputs (Update)</label>
        <div class="row">
            <div class="col-2">
                Can Update&nbsp;<input type="checkbox" data-col="update_mainTp" name="options[mainTp_can_update]" value="1" @if (isset($role->options['mainTp_can_update'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Yes/No&nbsp;<input type="checkbox" name="options[mainTp_yes_no]" value="1" @if (isset($role->options['mainTp_yes_no'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                First Name&nbsp;<input type="checkbox" name="options[mainTp_first_name]" value="1" @if (isset($role->options['mainTp_first_name'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Last Name&nbsp;<input type="checkbox" name="options[mainTp_last_name]" value="1" @if (isset($role->options['mainTp_last_name'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Primary Number&nbsp;<input type="checkbox" name="options[mainTp_phone1]" value="1" @if (isset($role->options['mainTp_phone1'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Secondary Number&nbsp;<input type="checkbox" name="options[mainTp_phone2]" value="1" @if (isset($role->options['mainTp_phone2'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Email Address&nbsp;<input type="checkbox" name="options[mainTp_email]" value="1" @if (isset($role->options['mainTp_email'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Enabled&nbsp;<input type="checkbox" name="options[mainTp_enabled]" value="1" @if (isset($role->options['mainTp_enabled'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Leverage&nbsp;<input type="checkbox" name="options[mainTp_leverage]" value="1" @if (isset($role->options['mainTp_leverage'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Username&nbsp;<input type="checkbox" name="options[mainTp_username]" value="1" @if (isset($role->options['mainTp_username'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Password&nbsp;<input type="checkbox" name="options[mainTp_pass]" value="1" @if (isset($role->options['mainTp_pass'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                USDT&nbsp;<input type="checkbox" name="options[mainTp_usdt]" value="1" @if (isset($role->options['mainTp_usdt'])) checked @endif />
            </div>
            <div class="col-2 update_mainTp">
                Country&nbsp;<input type="checkbox" name="options[mainTp_country]" value="1" @if (isset($role->options['mainTp_country'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-12 main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">MainTp Actions</label>
        <div class="row">
            <div class="col">
                Send Email&nbsp;<input type="checkbox" name="options[mainTp_actions_send_email]" value="1" @if (isset($role->options['mainTp_actions_send_email'])) checked @endif />
            </div>
            <div class="col">
                Create Money Transaction&nbsp;<input type="checkbox" name="options[mainTp_actions_create_money_transaction]" value="1" @if (isset($role->options['mainTp_actions_create_money_transaction'])) checked @endif />
            </div>
            <div class="col">
                Create Request&nbsp;<input type="checkbox" name="options[mainTp_actions_create_request]" value="1" @if (isset($role->options['mainTp_actions_create_request'])) checked @endif />
            </div>
            <div class="col">
                Open Order&nbsp;<input type="checkbox" name="options[mainTp_actions_open_order]" value="1" @if (isset($role->options['mainTp_actions_open_order'])) checked @endif />
            </div>
            <div class="col">
                Actions&nbsp;<input type="checkbox" name="options[mainTp_actions]" value="1" @if (isset($role->options['mainTp_actions'])) checked @endif />
            </div>
            <div class="col">
                Requests&nbsp;<input type="checkbox" name="options[mainTp_actions_Requests]" value="1" @if (isset($role->options['mainTp_actions_Requests'])) checked @endif />
            </div>
            <div class="col">
                LogIn As Client&nbsp;<input type="checkbox" name="options[mainTp_actions_login_as_client]" value="1" @if (isset($role->options['mainTp_actions_login_as_client'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 leads @if (isset($role->options['leads_show'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Actions</label>
        <div class="row">
            <div class="col">
                Open Real Account&nbsp;<input type="checkbox" name="options[leads_actions_open_real]" value="1" @if (isset($role->options['leads_actions_open_real'])) checked @endif />
            </div>
            <div class="col">
                Open Demo&nbsp;<input type="checkbox" name="options[leads_actions_open_demo]" value="1" @if (isset($role->options['leads_actions_open_demo'])) checked @endif />
            </div>
            <div class="col">
                Actions&nbsp;<input type="checkbox" name="options[leads_actions_actions]" value="1" @if (isset($role->options['leads_actions_open_demo'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 leads @if (isset($role->options['leads_show'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Cards</label>
        <div class="row">
            <div class="col">
                Comments&nbsp;<input type="checkbox" data-col="leads_comments" name="options[leads_cards_comments]" value="1" @if (isset($role->options['leads_cards_comments'])) checked @endif />
            </div>
            <div class="col">
                Actions&nbsp;<input type="checkbox" name="options[leads_cards_actions]" value="1" @if (isset($role->options['leads_cards_actions'])) checked @endif />
            </div>
            <div class="col">
                Accounts&nbsp;<input type="checkbox" name="options[leads_cards_accounts]" value="1" @if (isset($role->options['leads_cards_accounts'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 leads_comments @if (isset($role->options['leads_cards_comments'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Leads Comments</label>
        <div class="row">
            <div class="col">
                Add&nbsp;<input type="checkbox" name="options[leads_add_comments]" value="1" @if (isset($role->options['leads_add_comments'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[leads_update_comments]" value="1" @if (isset($role->options['leads_update_comments'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[leads_delete_comments]" value="1" @if (isset($role->options['leads_delete_comments'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">MainTp Cards</label>
        <div class="row">
            <div class="col">
                Comments&nbsp;<input type="checkbox" data-col="mainTp_comments" name="options[mainTp_cards_comments]" value="1" @if (isset($role->options['mainTp_cards_comments'])) checked @endif />
            </div>
            <div class="col">
                Chat&nbsp;<input type="checkbox" data-col="mainTp_chat" name="options[mainTp_cards_chat]" value="1" @if (isset($role->options['mainTp_cards_chat'])) checked @endif />
            </div>
            <div class="col">
                Actions&nbsp;<input type="checkbox" name="options[mainTp_cards_actions]" value="1" @if (isset($role->options['mainTp_cards_actions'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">Money Transaction</label>
        <div class="row">
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[mainTp_money_trx_update]" value="1" @if (isset($role->options['mainTp_money_trx_update'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[mainTp_money_trx_delete]" value="1" @if (isset($role->options['mainTp_money_trx_delete'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 mainTp_comments @if (isset($role->options['mainTp_cards_comments'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">MainTp Comments</label>
        <div class="row">
            <div class="col">
                Add&nbsp;<input type="checkbox" name="options[mainTp_add_comments]" value="1" @if (isset($role->options['mainTp_add_comments'])) checked @endif />
            </div>
            <div class="col">
                Update&nbsp;<input type="checkbox" name="options[mainTp_update_comments]" value="1" @if (isset($role->options['mainTp_update_comments'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[mainTp_delete_comments]" value="1" @if (isset($role->options['mainTp_delete_comments'])) checked @endif />
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 mainTp_chat @if (isset($role->options['mainTp_cards_chat'])) @else d-none @endif">
    <div class="border p-2 position-relative" style="border-radius: 10px">
        <span class="position-absolute badge" style="top:0px;right:0px;">
            <input type="checkbox" class="check-all"/>
        </span>
        <label class="form-label font-20" style="font-weight: bold">MainTp Chat</label>
        <div class="row">
            <div class="col">
                Add-Delete-Edit&nbsp;<input type="checkbox" name="options[mainTp_add_chat]" value="1" @if (isset($role->options['mainTp_add_chat'])) checked @endif />
            </div>
            {{-- <div class="col">
                Update&nbsp;<input type="checkbox" name="options[mainTp_update_chat]" value="1" @if (isset($role->options['mainTp_update_chat'])) checked @endif />
            </div>
            <div class="col">
                Delete&nbsp;<input type="checkbox" name="options[mainTp_delete_chat]" value="1" @if (isset($role->options['mainTp_delete_chat'])) checked @endif />
            </div> --}}
        </div>
    </div>
</div>
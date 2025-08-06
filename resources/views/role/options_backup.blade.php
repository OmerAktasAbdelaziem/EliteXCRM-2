<style>
/* Modern Permissions Design */
.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.permission-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.permission-card:hover {
    border-color: #6366f1;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
}

.permission-card.has-children {
    margin-bottom: 0.5rem;
}

.permission-card.conditional-child {
    margin-left: 2rem;
    margin-top: 1rem;
    border-left: 4px solid #6366f1;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    position: relative;
}

.permission-card.conditional-child::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 50%;
    width: 1.5rem;
    height: 2px;
    background: #6366f1;
    transform: translateY(-50%);
}

.permission-card.conditional-child .permission-header {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
}

.permission-header {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    padding: 1rem 1.25rem;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.permission-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: white;
}

.select-all-toggle {
    position: relative;
}

.select-all-checkbox {
    width: 20px;
    height: 20px;
    accent-color: white;
    cursor: pointer;
}

.permission-body {
    padding: 1.25rem;
}

.permission-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.75rem;
}

.permission-option {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 6px;
    transition: background-color 0.2s;
    cursor: pointer;
}

.permission-option:hover {
    background: #f8fafc;
}

.permission-option input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin-right: 0.5rem;
    accent-color: #6366f1;
    cursor: pointer;
}

.permission-option label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    margin: 0;
}

.permission-option.checked {
    background: #eff6ff;
    border: 1px solid #dbeafe;
}

.permission-option.checked label {
    color: #1e40af;
}

/* Animation for expanding conditional permissions */
.permission-conditional-section {
    transition: all 0.3s ease;
    overflow: hidden;
}

.permission-conditional-section.collapsed {
    max-height: 0;
    opacity: 0;
    margin: 0;
}

.permission-conditional-section.expanded {
    max-height: 1000px;
    opacity: 1;
    margin-top: 1rem;
}

/* Enhanced Legacy Permission Styling */
.enhanced-legacy-card {
    background: white;
    border: 1px solid #e5e7eb !important;
    border-radius: 12px !important;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.enhanced-legacy-card:hover {
    border-color: #6366f1 !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
}

.enhanced-legacy-card .form-label {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white !important;
    font-weight: 600;
    margin: 0 !important;
    padding: 1rem 1.25rem;
    border-radius: 12px 12px 0 0;
    position: relative;
}

.enhanced-legacy-card .row {
    padding: 1.25rem;
}

.enhanced-legacy-card .row .col {
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.enhanced-legacy-card .row .col input[type="checkbox"] {
    margin-left: 0.5rem;
    width: 16px;
    height: 16px;
    accent-color: #6366f1;
}

.enhanced-legacy-card .position-absolute.badge {
    top: 1rem !important;
    right: 1.25rem !important;
    background: transparent;
    border: none;
}

.enhanced-legacy-card .position-absolute.badge input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .permissions-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .permission-options {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 1200px) {
    .permissions-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<div class="permissions-grid">
    <!-- Leads Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-user-plus me-2"></i>Leads
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[leads_create]" value="1" id="leads_create" @if (isset($role->options['leads_create'])) checked @endif />
                    <label for="leads_create">Create</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" data-col="leads_table" name="options[leads_list]" value="1" id="leads_list" @if (isset($role->options['leads_list'])) checked @endif />
                    <label for="leads_list">List</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" data-col="leads" name="options[leads_show]" value="1" id="leads_show" @if (isset($role->options['leads_show'])) checked @endif />
                    <label for="leads_show">Show</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[leads_delete]" value="1" id="leads_delete" @if (isset($role->options['leads_delete'])) checked @endif />
                    <label for="leads_delete">Delete</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[leads_export]" value="1" id="leads_export" @if (isset($role->options['leads_export'])) checked @endif />
                    <label for="leads_export">Export</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" data-col="main_tp" name="options[leads_main_tp]" value="1" id="leads_main_tp" @if (isset($role->options['leads_main_tp'])) checked @endif />
                    <label for="leads_main_tp">MainTP (Real&Demo)</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" data-col="main_tp" name="options[leads_main_tp_demo]" value="1" id="leads_main_tp_demo" @if (isset($role->options['leads_main_tp_demo'])) checked @endif />
                    <label for="leads_main_tp_demo">MainTP (Demo)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Conditional Sections -->
    <div class="permission-conditional-section leads_table @if (isset($role->options['leads_list'])) expanded @else collapsed @endif">
        <div class="permission-card child-card">
            <div class="permission-header">
                <h4 class="permission-title">
                    <i class="bx bx-table me-2"></i>Leads Table Columns
                </h4>
                <div class="select-all-toggle">
                    <input type="checkbox" class="check-all select-all-checkbox" />
                </div>
            </div>
            <div class="permission-body">
                <div class="permission-options">
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_first_name]" value="1" id="leads_table_first_name" @if (isset($role->options['leads_table_first_name'])) checked @endif />
                        <label for="leads_table_first_name">First Name</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_last_name]" value="1" id="leads_table_last_name" @if (isset($role->options['leads_table_last_name'])) checked @endif />
                        <label for="leads_table_last_name">Last Name</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_email]" value="1" id="leads_table_email" @if (isset($role->options['leads_table_email'])) checked @endif />
                        <label for="leads_table_email">Email</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_phone1]" value="1" id="leads_table_phone1" @if (isset($role->options['leads_table_phone1'])) checked @endif />
                        <label for="leads_table_phone1">Primary Phone</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_phone2]" value="1" id="leads_table_phone2" @if (isset($role->options['leads_table_phone2'])) checked @endif />
                        <label for="leads_table_phone2">Secondary Phone</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_status]" value="1" id="leads_table_status" @if (isset($role->options['leads_table_status'])) checked @endif />
                        <label for="leads_table_status">Status</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_ftd]" value="1" id="leads_table_ftd" @if (isset($role->options['leads_table_ftd'])) checked @endif />
                        <label for="leads_table_ftd">FTD</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_enabled]" value="1" id="leads_table_enabled" @if (isset($role->options['leads_table_enabled'])) checked @endif />
                        <label for="leads_table_enabled">Enabled</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_user_id]" value="1" id="leads_table_user_id" @if (isset($role->options['leads_table_user_id'])) checked @endif />
                        <label for="leads_table_user_id">Assigned User</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_country]" value="1" id="leads_table_country" @if (isset($role->options['leads_table_country'])) checked @endif />
                        <label for="leads_table_country">Country</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_created_date]" value="1" id="leads_table_created_date" @if (isset($role->options['leads_table_created_date'])) checked @endif />
                        <label for="leads_table_created_date">Created Date</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_assigned_date]" value="1" id="leads_table_assigned_date" @if (isset($role->options['leads_table_assigned_date'])) checked @endif />
                        <label for="leads_table_assigned_date">Assigned Date</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_ftd_date]" value="1" id="leads_table_ftd_date" @if (isset($role->options['leads_table_ftd_date'])) checked @endif />
                        <label for="leads_table_ftd_date">FTD Date</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_last_comment]" value="1" id="leads_table_last_comment" @if (isset($role->options['leads_table_last_comment'])) checked @endif />
                        <label for="leads_table_last_comment">Last Comment</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_table_comments_count]" value="1" id="leads_table_comments_count" @if (isset($role->options['leads_table_comments_count'])) checked @endif />
                        <label for="leads_table_comments_count">Comments Count</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section leads_table @if (isset($role->options['leads_list'])) expanded @else collapsed @endif">
        <div class="permission-card child-card">
            <div class="permission-header">
                <h4 class="permission-title">
                    <i class="bx bx-tab me-2"></i>Leads Tabs
                </h4>
                <div class="select-all-toggle">
                    <input type="checkbox" class="check-all select-all-checkbox" />
                </div>
            </div>
            <div class="permission-body">
                <div class="permission-options">
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_new_clients]" value="1" id="leads_tabs_new_clients" @if (isset($role->options['leads_tabs_new_clients'])) checked @endif />
                        <label for="leads_tabs_new_clients">New Clients</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_call_back]" value="1" id="leads_tabs_call_back" @if (isset($role->options['leads_tabs_call_back'])) checked @endif />
                        <label for="leads_tabs_call_back">Call Back</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_no_answer]" value="1" id="leads_tabs_no_answer" @if (isset($role->options['leads_tabs_no_answer'])) checked @endif />
                        <label for="leads_tabs_no_answer">No Answer</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_not_now]" value="1" id="leads_tabs_not_now" @if (isset($role->options['leads_tabs_not_now'])) checked @endif />
                        <label for="leads_tabs_not_now">Not Now</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_wrong_number]" value="1" id="leads_tabs_wrong_number" @if (isset($role->options['leads_tabs_wrong_number'])) checked @endif />
                        <label for="leads_tabs_wrong_number">Wrong Number</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_follow_up]" value="1" id="leads_tabs_follow_up" @if (isset($role->options['leads_tabs_follow_up'])) checked @endif />
                        <label for="leads_tabs_follow_up">Follow Up</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_not_interested]" value="1" id="leads_tabs_not_interested" @if (isset($role->options['leads_tabs_not_interested'])) checked @endif />
                        <label for="leads_tabs_not_interested">Not Interested</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_do_not_call]" value="1" id="leads_tabs_do_not_call" @if (isset($role->options['leads_tabs_do_not_call'])) checked @endif />
                        <label for="leads_tabs_do_not_call">Do Not Call</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_call_back_later]" value="1" id="leads_tabs_call_back_later" @if (isset($role->options['leads_tabs_call_back_later'])) checked @endif />
                        <label for="leads_tabs_call_back_later">Call Back Later</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[leads_tabs_user_test]" value="1" id="leads_tabs_user_test" @if (isset($role->options['leads_tabs_user_test'])) checked @endif />
                        <label for="leads_tabs_user_test">User Test</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section leads @if (isset($role->options['leads_show'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-show me-2"></i>Leads Inputs (Show)
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">First Name</span>
                        <input type="checkbox" name="options[leads_show_first_name]" value="1" @if (isset($role->options['leads_show_first_name'])) checked @endif />
                    </div>
                    <div class="permission-item mt-1">
                        <span class="permission-text small">Hide</span>
                        <input type="checkbox" class="hide" name="options[leads_show_first_name_hide]" value="1" @if (isset($role->options['leads_show_first_name_hide'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Last Name</span>
                        <input type="checkbox" name="options[leads_show_last_name]" value="1" @if (isset($role->options['leads_show_last_name'])) checked @endif />
                    </div>
                    <div class="permission-item mt-1">
                        <span class="permission-text small">Hide</span>
                        <input type="checkbox" class="hide" name="options[leads_show_last_name_hide]" value="1" @if (isset($role->options['leads_show_last_name_hide'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Email Address</span>
                        <input type="checkbox" name="options[leads_show_email]" value="1" @if (isset($role->options['leads_show_email'])) checked @endif />
                    </div>
                    <div class="permission-item mt-1">
                        <span class="permission-text small">Hide</span>
                        <input type="checkbox" class="hide" name="options[leads_show_email_hide]" value="1" @if (isset($role->options['leads_show_email_hide'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Primary Number</span>
                        <input type="checkbox" name="options[leads_show_phone1]" value="1" @if (isset($role->options['leads_show_phone1'])) checked @endif />
                    </div>
                    <div class="permission-item mt-1">
                        <span class="permission-text small">Hide</span>
                        <input type="checkbox" class="hide" name="options[leads_show_phone1_hide]" value="1" @if (isset($role->options['leads_show_phone1_hide'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Secondary Number</span>
                        <input type="checkbox" name="options[leads_show_phone2]" value="1" @if (isset($role->options['leads_show_phone2'])) checked @endif />
                    </div>
                    <div class="permission-item mt-1">
                        <span class="permission-text small">Hide</span>
                        <input type="checkbox" class="hide" name="options[leads_show_phone2_hide]" value="1" @if (isset($role->options['leads_show_phone2_hide'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Sales Status</span>
                        <input type="checkbox" name="options[leads_show_status]" value="1" @if (isset($role->options['leads_show_status'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">FTD</span>
                        <input type="checkbox" name="options[leads_show_ftd]" value="1" @if (isset($role->options['leads_show_ftd'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Enabled</span>
                        <input type="checkbox" name="options[leads_show_enabled]" value="1" @if (isset($role->options['leads_show_enabled'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Assigned User</span>
                        <input type="checkbox" name="options[leads_show_user_id]" value="1" @if (isset($role->options['leads_show_user_id'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Username</span>
                        <input type="checkbox" name="options[leads_show_username]" value="1" @if (isset($role->options['leads_show_username'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Password</span>
                        <input type="checkbox" name="options[leads_show_pass]" value="1" @if (isset($role->options['leads_show_pass'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">USDT</span>
                        <input type="checkbox" name="options[leads_show_usdt]" value="1" @if (isset($role->options['leads_show_usdt'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">FTD Amount</span>
                        <input type="checkbox" name="options[leads_show_ftd_amount]" value="1" @if (isset($role->options['leads_show_ftd_amount'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Leverage</span>
                        <input type="checkbox" name="options[leads_show_leverage]" value="1" @if (isset($role->options['leads_show_leverage'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Account Type</span>
                        <input type="checkbox" name="options[leads_show_account_type]" value="1" @if (isset($role->options['leads_show_account_type'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Asset Group</span>
                        <input type="checkbox" name="options[leads_show_asset_group]" value="1" @if (isset($role->options['leads_show_asset_group'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Country</span>
                        <input type="checkbox" name="options[leads_show_country]" value="1" @if (isset($role->options['leads_show_country'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">First Owner</span>
                        <input type="checkbox" name="options[leads_show_first_owner]" value="1" @if (isset($role->options['leads_show_first_owner'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Team</span>
                        <input type="checkbox" name="options[leads_show_team]" value="1" @if (isset($role->options['leads_show_team'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Last Deposit Amount</span>
                        <input type="checkbox" name="options[leads_show_lda]" value="1" @if (isset($role->options['leads_show_lda'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">First Comment Date</span>
                        <input type="checkbox" name="options[leads_show_first_comment_date]" value="1" @if (isset($role->options['leads_show_first_comment_date'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">First Comment Owner</span>
                        <input type="checkbox" name="options[leads_show_first_comment_owner]" value="1" @if (isset($role->options['leads_show_first_comment_owner'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Assigned Date</span>
                        <input type="checkbox" name="options[leads_show_assigned_date]" value="1" @if (isset($role->options['leads_show_assigned_date'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">FTD Date</span>
                        <input type="checkbox" name="options[leads_show_ftd_date]" value="1" @if (isset($role->options['leads_show_ftd_date'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Created Date</span>
                        <input type="checkbox" name="options[leads_show_created_date]" value="1" @if (isset($role->options['leads_show_created_date'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Modified Date</span>
                        <input type="checkbox" name="options[leads_show_modified_date]" value="1" @if (isset($role->options['leads_show_modified_date'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Registration Date</span>
                        <input type="checkbox" name="options[leads_show_registration_date]" value="1" @if (isset($role->options['leads_show_registration_date'])) checked @endif />
                    </div>
                </div>
                <div class="col-2">
                    <div class="permission-item">
                        <span class="permission-text">Age</span>
                        <input type="checkbox" name="options[leads_show_age]" value="1" @if (isset($role->options['leads_show_age'])) checked @endif />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section leads @if (isset($role->options['leads_show'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-cog me-2"></i>Leads Actions
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Open Real Account<input type="checkbox" name="options[leads_actions_open_real]" value="1" @if (isset($role->options['leads_actions_open_real'])) checked @endif />
                </div>
                <div class="col">
                    Open Demo<input type="checkbox" name="options[leads_actions_open_demo]" value="1" @if (isset($role->options['leads_actions_open_demo'])) checked @endif />
                </div>
                <div class="col">
                    Actions<input type="checkbox" name="options[leads_actions_actions]" value="1" @if (isset($role->options['leads_actions_open_demo'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section leads @if (isset($role->options['leads_show'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-card-list me-2"></i>Leads Cards
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Comments<input type="checkbox" data-col="leads_comments" name="options[leads_cards_comments]" value="1" @if (isset($role->options['leads_cards_comments'])) checked @endif />
                </div>
                <div class="col">
                    Actions<input type="checkbox" name="options[leads_cards_actions]" value="1" @if (isset($role->options['leads_cards_actions'])) checked @endif />
                </div>
                <div class="col">
                    Accounts<input type="checkbox" name="options[leads_cards_accounts]" value="1" @if (isset($role->options['leads_cards_accounts'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section leads @if (isset($role->options['leads_show'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-cog me-2"></i>Leads Actions
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Open Real Account<input type="checkbox" name="options[leads_actions_open_real]" value="1" @if (isset($role->options['leads_actions_open_real'])) checked @endif />
                </div>
                <div class="col">
                    Open Demo<input type="checkbox" name="options[leads_actions_open_demo]" value="1" @if (isset($role->options['leads_actions_open_demo'])) checked @endif />
                </div>
                <div class="col">
                    Actions<input type="checkbox" name="options[leads_actions_actions]" value="1" @if (isset($role->options['leads_actions_open_demo'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section leads_comments @if (isset($role->options['leads_cards_comments'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-comment me-2"></i>Leads Comments
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Add<input type="checkbox" name="options[leads_add_comments]" value="1" @if (isset($role->options['leads_add_comments'])) checked @endif />
                </div>
                <div class="col">
                    Update<input type="checkbox" name="options[leads_update_comments]" value="1" @if (isset($role->options['leads_update_comments'])) checked @endif />
                </div>
                <div class="col">
                    Delete<input type="checkbox" name="options[leads_delete_comments]" value="1" @if (isset($role->options['leads_delete_comments'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-edit me-2"></i>MainTp Inputs (Update)
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col-2">
                    Can Update<input type="checkbox" data-col="update_mainTp" name="options[mainTp_can_update]" value="1" @if (isset($role->options['mainTp_can_update'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Yes/No<input type="checkbox" name="options[mainTp_yes_no]" value="1" @if (isset($role->options['mainTp_yes_no'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    First Name<input type="checkbox" name="options[mainTp_first_name]" value="1" @if (isset($role->options['mainTp_first_name'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Last Name<input type="checkbox" name="options[mainTp_last_name]" value="1" @if (isset($role->options['mainTp_last_name'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Primary Number<input type="checkbox" name="options[mainTp_phone1]" value="1" @if (isset($role->options['mainTp_phone1'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Secondary Number<input type="checkbox" name="options[mainTp_phone2]" value="1" @if (isset($role->options['mainTp_phone2'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Email Address<input type="checkbox" name="options[mainTp_email]" value="1" @if (isset($role->options['mainTp_email'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Enabled<input type="checkbox" name="options[mainTp_enabled]" value="1" @if (isset($role->options['mainTp_enabled'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Leverage<input type="checkbox" name="options[mainTp_leverage]" value="1" @if (isset($role->options['mainTp_leverage'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Username<input type="checkbox" name="options[mainTp_username]" value="1" @if (isset($role->options['mainTp_username'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Password<input type="checkbox" name="options[mainTp_pass]" value="1" @if (isset($role->options['mainTp_pass'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    USDT<input type="checkbox" name="options[mainTp_usdt]" value="1" @if (isset($role->options['mainTp_usdt'])) checked @endif />
                </div>
                <div class="col-2 update_mainTp">
                    Country<input type="checkbox" name="options[mainTp_country]" value="1" @if (isset($role->options['mainTp_country'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-cog me-2"></i>MainTp Actions
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Send Email<input type="checkbox" name="options[mainTp_actions_send_email]" value="1" @if (isset($role->options['mainTp_actions_send_email'])) checked @endif />
                </div>
                <div class="col">
                    Create Money Transaction<input type="checkbox" name="options[mainTp_actions_create_money_transaction]" value="1" @if (isset($role->options['mainTp_actions_create_money_transaction'])) checked @endif />
                </div>
                <div class="col">
                    Create Request<input type="checkbox" name="options[mainTp_actions_create_request]" value="1" @if (isset($role->options['mainTp_actions_create_request'])) checked @endif />
                </div>
                <div class="col">
                    Open Order<input type="checkbox" name="options[mainTp_actions_open_order]" value="1" @if (isset($role->options['mainTp_actions_open_order'])) checked @endif />
                </div>
                <div class="col">
                    Actions<input type="checkbox" name="options[mainTp_actions]" value="1" @if (isset($role->options['mainTp_actions'])) checked @endif />
                </div>
                <div class="col">
                    Requests<input type="checkbox" name="options[mainTp_actions_Requests]" value="1" @if (isset($role->options['mainTp_actions_Requests'])) checked @endif />
                </div>
                <div class="col">
                    LogIn As Client<input type="checkbox" name="options[mainTp_actions_login_as_client]" value="1" @if (isset($role->options['mainTp_actions_login_as_client'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-card-list me-2"></i>MainTp Cards
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Comments<input type="checkbox" data-col="mainTp_comments" name="options[mainTp_cards_comments]" value="1" @if (isset($role->options['mainTp_cards_comments'])) checked @endif />
                </div>
                <div class="col">
                    Chat<input type="checkbox" data-col="mainTp_chat" name="options[mainTp_cards_chat]" value="1" @if (isset($role->options['mainTp_cards_chat'])) checked @endif />
                </div>
                <div class="col">
                    Actions<input type="checkbox" name="options[mainTp_cards_actions]" value="1" @if (isset($role->options['mainTp_cards_actions'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section main_tp @if (isset($role->options['leads_main_tp']) || isset($role->options['leads_main_tp_demo'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-money me-2"></i>Money Transaction
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Update<input type="checkbox" name="options[mainTp_money_trx_update]" value="1" @if (isset($role->options['mainTp_money_trx_update'])) checked @endif />
                </div>
                <div class="col">
                    Delete<input type="checkbox" name="options[mainTp_money_trx_delete]" value="1" @if (isset($role->options['mainTp_money_trx_delete'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section mainTp_comments @if (isset($role->options['mainTp_cards_comments'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-comment me-2"></i>MainTp Comments
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Add<input type="checkbox" name="options[mainTp_add_comments]" value="1" @if (isset($role->options['mainTp_add_comments'])) checked @endif />
                </div>
                <div class="col">
                    Update<input type="checkbox" name="options[mainTp_update_comments]" value="1" @if (isset($role->options['mainTp_update_comments'])) checked @endif />
                </div>
                <div class="col">
                    Delete<input type="checkbox" name="options[mainTp_delete_comments]" value="1" @if (isset($role->options['mainTp_delete_comments'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <div class="permission-conditional-section mainTp_chat @if (isset($role->options['mainTp_cards_chat'])) expanded @else collapsed @endif">
        <div class="enhanced-legacy-card">
            <div class="form-label">
                <i class="bx bx-chat me-2"></i>MainTp Chat
                <span class="position-absolute badge">
                    <input type="checkbox" class="check-all"/>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    Add-Delete-Edit<input type="checkbox" name="options[mainTp_add_chat]" value="1" @if (isset($role->options['mainTp_add_chat'])) checked @endif />
                </div>
            </div>
        </div>
    </div>

    <!-- Users Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-users me-2"></i>Users
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[users_create]" value="1" id="users_create" @if (isset($role->options['users_create'])) checked @endif />
                    <label for="users_create">Create</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[users_list]" value="1" id="users_list" @if (isset($role->options['users_list'])) checked @endif />
                    <label for="users_list">List</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[users_delete]" value="1" id="users_delete" @if (isset($role->options['users_delete'])) checked @endif />
                    <label for="users_delete">Delete</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[users_show]" value="1" id="users_show" @if (isset($role->options['users_show'])) checked @endif />
                    <label for="users_show">Show</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[users_update]" value="1" id="users_update" @if (isset($role->options['users_update'])) checked @endif />
                    <label for="users_update">Update</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Parts Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-cog me-2"></i>Parts
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[parts_create]" value="1" id="parts_create" @if (isset($role->options['parts_create'])) checked @endif />
                    <label for="parts_create">Create</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[parts_list]" value="1" id="parts_list" @if (isset($role->options['parts_list'])) checked @endif />
                    <label for="parts_list">List</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[parts_show]" value="1" id="parts_show" @if (isset($role->options['parts_show'])) checked @endif />
                    <label for="parts_show">Show</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[parts_update]" value="1" id="parts_update" @if (isset($role->options['parts_update'])) checked @endif />
                    <label for="parts_update">Update</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Teams Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-group me-2"></i>Teams
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[teams_create]" value="1" id="teams_create" @if (isset($role->options['teams_create'])) checked @endif />
                    <label for="teams_create">Create</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[teams_list]" value="1" id="teams_list" @if (isset($role->options['teams_list'])) checked @endif />
                    <label for="teams_list">List</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[teams_show]" value="1" id="teams_show" @if (isset($role->options['teams_show'])) checked @endif />
                    <label for="teams_show">Show</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[teams_update]" value="1" id="teams_update" @if (isset($role->options['teams_update'])) checked @endif />
                    <label for="teams_update">Update</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-list-check me-2"></i>Status
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[status_create]" value="1" id="status_create" @if (isset($role->options['status_create'])) checked @endif />
                    <label for="status_create">Create</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[status_list]" value="1" id="status_list" @if (isset($role->options['status_list'])) checked @endif />
                    <label for="status_list">List</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[status_show]" value="1" id="status_show" @if (isset($role->options['status_show'])) checked @endif />
                    <label for="status_show">Show</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[status_update]" value="1" id="status_update" @if (isset($role->options['status_update'])) checked @endif />
                    <label for="status_update">Update</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[status_delete]" value="1" id="status_delete" @if (isset($role->options['status_delete'])) checked @endif />
                    <label for="status_delete">Delete</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-shield-check me-2"></i>Roles
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[roles_create]" value="1" id="roles_create" @if (isset($role->options['roles_create'])) checked @endif />
                    <label for="roles_create">Create</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[roles_list]" value="1" id="roles_list" @if (isset($role->options['roles_list'])) checked @endif />
                    <label for="roles_list">List</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[roles_show]" value="1" id="roles_show" @if (isset($role->options['roles_show'])) checked @endif />
                    <label for="roles_show">Show</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[roles_update]" value="1" id="roles_update" @if (isset($role->options['roles_update'])) checked @endif />
                    <label for="roles_update">Update</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[roles_delete]" value="1" id="roles_delete" @if (isset($role->options['roles_delete'])) checked @endif />
                    <label for="roles_delete">Delete</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Permissions -->
    
    <!-- Settings Permission -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-cog me-2"></i>Settings
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[settings]" value="1" id="settings" @if (isset($role->options['settings'])) checked @endif />
                    <label for="settings">Access Settings</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Retention Permission -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-user-check me-2"></i>Retention
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[retention]" value="1" id="retention" @if (isset($role->options['retention'])) checked @endif />
                    <label for="retention">Access Retention</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Permission -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-message-square-detail me-2"></i>Requests
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[requests]" value="1" id="requests" @if (isset($role->options['requests'])) checked @endif />
                    <label for="requests">Access Requests</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Permission -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-bar-chart-alt-2 me-2"></i>Reports
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[reports_list]" value="1" id="reports_list" @if (isset($role->options['reports_list'])) checked @endif />
                    <label for="reports_list">Access Reports</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Permission -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-dashboard me-2"></i>Overview
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[overview]" value="1" id="overview" @if (isset($role->options['overview'])) checked @endif />
                    <label for="overview">Access Overview</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Marketing Permissions -->
    <div class="permission-card">
        <div class="permission-header">
            <h4 class="permission-title">
                <i class="bx bx-envelope me-2"></i>Email Marketing
            </h4>
            <div class="select-all-toggle">
                <input type="checkbox" class="check-all select-all-checkbox" />
            </div>
        </div>
        <div class="permission-body">
            <div class="permission-options">
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_sender_emails]" data-col="sender_email" value="1" id="emails_sender_emails" @if (isset($role->options['emails_sender_emails'])) checked @endif />
                    <label for="emails_sender_emails">Sender Emails</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_template_list]" value="1" id="emails_template_list" @if (isset($role->options['emails_template_list'])) checked @endif />
                    <label for="emails_template_list">List Templates</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_template_create]" value="1" id="emails_template_create" @if (isset($role->options['emails_template_create'])) checked @endif />
                    <label for="emails_template_create">Create Templates</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_template_show]" value="1" id="emails_template_show" @if (isset($role->options['emails_template_show'])) checked @endif />
                    <label for="emails_template_show">Show Templates</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_template_update]" value="1" id="emails_template_update" @if (isset($role->options['emails_template_update'])) checked @endif />
                    <label for="emails_template_update">Update Templates</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_template_delete]" value="1" id="emails_template_delete" @if (isset($role->options['emails_template_delete'])) checked @endif />
                    <label for="emails_template_delete">Delete Templates</span>
                </div>
                <div class="permission-option">
                    <input type="checkbox" name="options[emails_send]" value="1" id="emails_send" @if (isset($role->options['emails_send'])) checked @endif />
                    <label for="emails_send">Send Emails</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sender Emails (Conditional) -->
    <div class="permission-conditional-section sender_email @if (isset($role->options['emails_sender_emails'])) expanded @else collapsed @endif">
        <div class="permission-card conditional-child">
            <div class="permission-header">
                <h4 class="permission-title">
                    <i class="bx bx-mail-send me-2"></i>Sender Emails Details
                </h4>
                <div class="select-all-toggle">
                    <input type="checkbox" class="check-all select-all-checkbox" />
                </div>
            </div>
            <div class="permission-body">
                <div class="permission-options">
                    <div class="permission-option">
                        <input type="checkbox" name="options[sender_email_list]" value="1" id="sender_email_list" @if (isset($role->options['sender_email_list'])) checked @endif />
                        <label for="sender_email_list">List</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[sender_email_create]" value="1" id="sender_email_create" @if (isset($role->options['sender_email_create'])) checked @endif />
                        <label for="sender_email_create">Create</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[sender_email_show]" value="1" id="sender_email_show" @if (isset($role->options['sender_email_show'])) checked @endif />
                        <label for="sender_email_show">Show</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[sender_email_update]" value="1" id="sender_email_update" @if (isset($role->options['sender_email_update'])) checked @endif />
                        <label for="sender_email_update">Update</span>
                    </div>
                    <div class="permission-option">
                        <input type="checkbox" name="options[sender_email_delete]" value="1" id="sender_email_delete" @if (isset($role->options['sender_email_delete'])) checked @endif />
                        <label for="sender_email_delete">Delete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

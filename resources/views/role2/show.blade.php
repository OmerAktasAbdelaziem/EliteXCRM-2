@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .permissions-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .permission-item {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .permission-item:last-child {
            border-bottom: none;
        }
        
        .permission-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            cursor: pointer;
            background: #fff;
            transition: all 0.3s ease;
        }
        
        .permission-header:hover {
            background: #f8f9fa;
        }
        
        .permission-info {
            display: flex;
            align-items: center;
            flex: 1;
        }
        
        .permission-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }
        
        .permission-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }
        
        .permission-details h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .permission-details p {
            margin: 0;
            font-size: 14px;
            color: #718096;
        }
        
        .permission-toggle {
            margin-left: auto;
            margin-right: 16px;
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e0;
            transition: 0.3s;
            border-radius: 26px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .toggle-switch input:checked + .toggle-slider {
            background-color: #48bb78;
        }
        
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        
        .expand-icon {
            width: 20px;
            height: 20px;
            color: #a0aec0;
            transition: transform 0.3s ease;
        }
        
        .permission-item.expanded .expand-icon {
            transform: rotate(180deg);
        }
        
        .permission-actions {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #f7fafc;
        }
        
        .permission-item.expanded .permission-actions {
            max-height: 2000px; /* Increased to accommodate all client fields */
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            padding: 20px 24px;
        }
        
        .action-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .action-item:hover {
            border-color: #cbd5e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .action-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
        }
        
        .action-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            color: #718096;
        }
        
        .checkbox-wrapper {
            position: relative;
        }
        
        .custom-checkbox {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e0;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .custom-checkbox:checked {
            background: #4299e1;
            border-color: #4299e1;
        }
        
        .custom-checkbox:checked::before {
            content: '✓';
            display: block;
            color: white;
            font-size: 12px;
            text-align: center;
            line-height: 14px;
        }
        
        .permission-item.disabled {
            opacity: 0.6;
        }
        
        .permission-item.disabled .permission-actions {
            display: none;
        }
        
        /* Specific styling for client page permissions */
        .permission-item[data-permission="client_page"] .permission-actions {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-out;
        }
        
        .permission-item[data-permission="client_page"].expanded .permission-actions {
            max-height: none; /* Remove height restriction for client permissions */
            overflow: visible;
        }
        
        .client-preview-container {
            max-height: 800px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        
        .client-preview-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .client-preview-container::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 4px;
        }
        
        .client-preview-container::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }
        
        .client-preview-container::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        .modern-form-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #f8f9fa;
        }
        
        .section-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .section-subtitle {
            margin: 4px 0 0 0;
            font-size: 14px;
            color: #718096;
        }
        
        .section-content {
            padding: 24px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            background: #f8f9fa;
            border-top: 1px solid #e2e8f0;
        }
        
        .btn-group {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        
        .btn-primary {
            background: #4299e1;
            color: white;
        }
        
        .btn-primary:hover {
            background: #3182ce;
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            background: #cbd5e0;
        }
        
        .btn-info {
            background: #38b2ac;
            color: white;
        }
        
        .btn-info:hover {
            background: #319795;
        }
    </style>

@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('fail'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('fail') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form name="addform" id="addform" method="POST" action="{{ $role->getKey()?route('role.update',$role->getKey()):route('role.store') }}">
                    @csrf
                    @if ($role->getKey())
                        @method('PUT')
                    @endif
                    <!-- Basic Information Section -->
                    <div class="modern-form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                {{ isset($role) && $role->getKey() ? 'Edit Role' : 'Create Role' }}
                            </h3>
                            <p class="section-subtitle">
                                {{ isset($role) && $role->getKey() ? 'Modify role permissions and settings' : 'Set up a new role with permissions' }}
                            </p>
                        </div>
                        <div class="section-content">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name">Role Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name',$role->name) }}" placeholder="Enter role name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="modern-form-section">
                        <div class="section-header">
                            <p class="section-subtitle">Configure what this role can access and modify</p>
                        </div>

                        <div class="permissions-container">
                            <!-- Leads Management -->
                            <div class="permission-item" data-permission="leads">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Users/People -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path fill-rule="evenodd" d="M2 13a5 5 0 0110 0v1H2v-1zm12 1v-1a7 7 0 10-14 0v1a2 2 0 002 2h10a2 2 0 002-2z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Leads Management</h4>
                                            <p>Access to lead data, viewing, and management features</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="leads">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Eye icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_show]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Plus icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Pencil icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_delete]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Leads Table
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_list]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Chat bubble icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M18 10c0 3.866-3.582 7-8 7a8.96 8.96 0 01-3.468-.684l-4.032 1.008a1 1 0 01-1.224-1.224l1.008-4.032A8.96 8.96 0 012 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                                                </svg>
                                                Leads Comments
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_cards_comments]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Refresh/update icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Update Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_can_update]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Maint TP -->
                            <div class="permission-item" data-permission="maint_tp">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Cog/Settings -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11.3 1.046a1 1 0 00-2.6 0l-.2.637a1 1 0 01-1.516.54l-.547-.316a1 1 0 00-1.366.366l-.5.866a1 1 0 01-1.13.49l-.62-.155a1 1 0 00-1.2 1.2l.155.62a1 1 0 01-.49 1.13l-.866.5a1 1 0 00-.366 1.366l.316.547a1 1 0 01-.54 1.516l-.637.2a1 1 0 000 2.6l.637.2a1 1 0 01.54 1.516l-.316.547a1 1 0 00.366 1.366l.866.5a1 1 0 01.49 1.13l-.155.62a1 1 0 001.2 1.2l.62-.155a1 1 0 011.13.49l.5.866a1 1 0 001.366.366l.547-.316a1 1 0 011.516.54l.2.637a1 1 0 002.6 0l.2-.637a1 1 0 011.516-.54l.547.316a1 1 0 001.366-.366l.5-.866a1 1 0 011.13-.49l.62.155a1 1 0 001.2-1.2l-.155-.62a1 1 0 01.49-1.13l.866-.5a1 1 0 00.366-1.366l-.316-.547a1 1 0 01.54-1.516l.637-.2a1 1 0 000-2.6l-.637-.2a1 1 0 01-.54-1.516l.316-.547a1 1 0 00-.366-1.366l-.866-.5a1 1 0 01-.49-1.13l.155-.62a1 1 0 00-1.2-1.2l-.62.155a1 1 0 01-1.13-.49l-.5-.866a1 1 0 00-1.366-.366l-.547.316a1 1 0 01-1.516-.54l-.2-.637zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Maint TP</h4>
                                            <p>Access to main tp and client trading management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="maint_tp">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Chart/Graph -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 17a1 1 0 01-1-1V7a1 1 0 112 0v9a1 1 0 01-1 1zm4-4a1 1 0 011-1h1a1 1 0 011 1v4a1 1 0 11-2 0v-4zm5-7a1 1 0 011 1v11a1 1 0 11-2 0V7a1 1 0 011-1zm4 3a1 1 0 011 1v8a1 1 0 11-2 0v-8a1 1 0 011-1z"/>
                                                </svg>
                                                View Trading
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_main_tp]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus Circle -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Orders
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[trading_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Clipboard List -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 00-1 1v1H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-3V3a1 1 0 00-1-1H9zm0 2V3h2v1H9zm-2 4a1 1 0 100 2h6a1 1 0 100-2H7zm0 4a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                                                </svg>
                                                Manage Orders
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[trading_manage]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Beaker (Demo) -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2a1 1 0 001 1v5.586l-3.293 3.293A1 1 0 004 16h12a1 1 0 00.707-1.707L13 10.586V5a1 1 0 001-1V3a1 1 0 00-1-1H6zm2 2V3h4v1H8zm-1 2h6v5.586l3.293 3.293A1 1 0 0116 16H4a1 1 0 01-.707-1.707L6 9.586V4z" clip-rule="evenodd"/>
                                                </svg>
                                                Demo Trading
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_main_tp_demo]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Update Trading
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[mainTp_can_update]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Chat Bubble -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M18 10c0 3.866-3.582 7-8 7a8.96 8.96 0 01-3.468-.684l-4.032 1.008a1 1 0 01-1.224-1.224l1.008-4.032A8.96 8.96 0 012 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                                                </svg>
                                                TP Comments
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[mainTp_cards_comments]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Message Dots -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M18 10c0 3.866-3.582 7-8 7a8.96 8.96 0 01-3.468-.684l-4.032 1.008a1 1 0 01-1.224-1.224l1.008-4.032A8.96 8.96 0 012 10c0-3.866 3.582-7 8-7s8 3.134 8 7zm-9-2a1 1 0 112 0 1 1 0 01-2 0zm4 1a1 1 0 100-2 1 1 0 000 2zm-8 0a1 1 0 100-2 1 1 0 000 2z"/>
                                                </svg>
                                                TP Chat
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[mainTp_cards_chat]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Users Permission -->
                            <div class="permission-item" data-permission="user">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>User</h4>
                                            <p>Access to user management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="user">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Users</div><input type="checkbox" class="custom-checkbox" name="options[leads_main_tp]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create User</div><input type="checkbox" class="custom-checkbox" name="options[trading_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit User</div><input type="checkbox" class="custom-checkbox" name="options[trading_manage]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>Delete User</div><input type="checkbox" class="custom-checkbox" name="options[leads_main_tp_demo]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Parts Permission -->
                            <div class="permission-item" data-permission="parts">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Parts Management</h4>
                                            <p>Manage parts , create, and edit</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="parts">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Parts</div><input type="checkbox" class="custom-checkbox" name="options[parts_view]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Parts</div><input type="checkbox" class="custom-checkbox" name="options[parts_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Parts</div><input type="checkbox" class="custom-checkbox" name="options[parts_edit]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Delete Parts</div><input type="checkbox" class="custom-checkbox" name="options[parts_sender_parts]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Teams Permission -->
                            <div class="permission-item" data-permission="teams">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Teams</h4>
                                            <p>Access to teams</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="teams">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Teams</div><input type="checkbox" class="custom-checkbox" name="options[leads_main_tp]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Team</div><input type="checkbox" class="custom-checkbox" name="options[trading_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Team</div><input type="checkbox" class="custom-checkbox" name="options[trading_manage]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>Delete Team</div><input type="checkbox" class="custom-checkbox" name="options[leads_main_tp_demo]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reports & Analytics -->
                            <div class="permission-item" data-permission="reports">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Reports & Analytics</h4>
                                            <p>Access to system reports and analytics dashboard</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="reports">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Reports</div><input type="checkbox" class="custom-checkbox" name="options[reports_view]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Reports</div><input type="checkbox" class="custom-checkbox" name="options[reports_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Reports</div><input type="checkbox" class="custom-checkbox" name="options[reports_edit]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4h8.905l-1.972 1.972a1 1 0 101.414 1.414L14.414 6H16a1 1 0 100-2H3z"/></svg>Export Data</div><input type="checkbox" class="custom-checkbox" name="options[reports_export]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Retention Permission -->
                            <div class="permission-item" data-permission="retention">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Retention</h4>
                                            <p>Access to retention managment</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="retention">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Retention</div><input type="checkbox" class="custom-checkbox" name="options[leads_main_tp]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Retention</div><input type="checkbox" class="custom-checkbox" name="options[trading_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>Delete Retention</div><input type="checkbox" class="custom-checkbox" name="options[leads_main_tp_demo]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Permission -->
                            <div class="permission-item" data-permission="request">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Paper Plane (Request) -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.94 2.94a1.5 1.5 0 012.12 0l11.5 11.5a1.5 1.5 0 010 2.12l-2.12 2.12a1.5 1.5 0 01-2.12 0l-11.5-11.5a1.5 1.5 0 010-2.12l2.12-2.12zM4 4l12 12"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Request</h4>
                                            <p>Access to Request Management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="request">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Inbox/Document Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm2 0v10h12V5H4zm6 3a3 3 0 100 6 3 3 0 000-6z"/>
                                                </svg>
                                                View Request Page
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_main_tp]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Permission -->
                            <div class="permission-item" data-permission="status">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Badge/Check Circle -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm3.707 5.707a1 1 0 00-1.414-1.414L9 9.586 7.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Status</h4>
                                            <p>Access to Status Management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="status">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Status Page
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[status_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Status
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[status_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Status
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[status_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Status
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[status_delete]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Roles Permission -->
                            <div class="permission-item" data-permission="roles">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Roles</h4>
                                            <p>Access to Roles Management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="roles">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Roles Page</div><input type="checkbox" class="custom-checkbox" name="options[emails_view]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Role</div><input type="checkbox" class="custom-checkbox" name="options[emails_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Role</div><input type="checkbox" class="custom-checkbox" name="options[emails_edit]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Delete Role</div><input type="checkbox" class="custom-checkbox" name="options[emails_sender_emails]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Management -->
                            <div class="permission-item" data-permission="emails">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Email Management</h4>
                                            <p>Email templates, campaigns, and sender management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="emails">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Emails</div><input type="checkbox" class="custom-checkbox" name="options[emails_view]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Templates</div><input type="checkbox" class="custom-checkbox" name="options[emails_create]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Templates</div><input type="checkbox" class="custom-checkbox" name="options[emails_edit]"></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails</div><input type="checkbox" class="custom-checkbox" name="options[emails_sender_emails]"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banks Management -->
                            <div class="permission-item" data-permission="banks">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Bank/Building -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2C9.447 2 9 2.447 9 3v1.382l-6.447 2.684A1 1 0 002 8v1a1 1 0 001 1h1v6H3a1 1 0 100 2h14a1 1 0 100-2h-1v-6h1a1 1 0 001-1V8a1 1 0 00-.553-.934L11 4.382V3c0-.553-.447-1-1-1zm0 2.236L16.382 7H3.618L10 4.236zM5 10h2v6H5v-6zm4 0h2v6H9v-6zm4 0h2v6h-2v-6z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Banks Management</h4>
                                            <p>Manage bank accounts and settings</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="banks">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Banks
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[banks_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Bank
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[banks_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Bank
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[banks_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Bank
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[banks_sender_emails]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assets Management -->
                            <div class="permission-item" data-permission="assets">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Safe/Locker -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <rect x="3" y="5" width="14" height="10" rx="2" />
                                                <circle cx="10" cy="10" r="2.5" />
                                                <rect x="9.25" y="9.25" width="1.5" height="1.5" rx="0.75" />
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Assets Management</h4>
                                            <p>Manage assets and settings</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="assets">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Assets
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[assets_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Asset
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[assets_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Asset
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[assets_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Asset
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[assets_sender_emails]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Asset Groups Management -->
                            <div class="permission-item" data-permission="asset_groups">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Folder Group -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Asset Groups Management</h4>
                                            <p>Manage asset groups and settings</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="asset_groups">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Asset Groups
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[asset_groups_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Asset Group
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[asset_groups_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Asset Group
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[asset_groups_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Asset Group
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[asset_groups_sender_emails]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pipeline Management -->
                            <div class="permission-item" data-permission="pipeline">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Pipeline/Flow -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <rect x="2" y="8" width="16" height="4" rx="2"/>
                                                <circle cx="4" cy="10" r="2"/>
                                                <circle cx="16" cy="10" r="2"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Pipeline Management</h4>
                                            <p>Manage pipelines and settings</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="pipeline">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Pipelines
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[pipeline_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Pipeline
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[pipeline_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Pipeline
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[pipeline_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Pipeline
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[pipeline_sender_emails]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Client Page Permissions -->
                            <div class="permission-item" data-permission="client_page">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Client Page Permissions</h4>
                                            <p>Control what client information can be viewed and edited</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="client_page">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <!-- Client Page Preview -->
                                    <div style="padding: 20px 24px; background: #f8f9fa;">
                                        <!-- Client Header Card -->
                                        <div style="background: #0d6efd; margin-bottom: 20px; border-radius: 0; box-shadow: none !important;">
                                            <div style="padding: 15px 20px 5px 20px;">
                                                <div style="display: flex; align-items: flex-start; gap: 20px; color: white;">
                                                    <!-- Left Section -->
                                                    <div style="flex: 1;">
                                                        <div style="display: flex; gap: 20px; margin-bottom: 12px;">
                                                            <div>
                                                                <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">ID</small>
                                                                <small style="font-size: 13px; font-weight: 500;">
                                                                    <a href="#" style="color: white; text-decoration: none;">#12345</a>
                                                                </small>
                                                            </div>
                                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                                <div>
                                                                    <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">TP</small>
                                                                    <small style="font-size: 13px; font-weight: 500;">
                                                                        <a href="#" style="color: white; text-decoration: none;">MT5-Live-001</a>
                                                                    </small>
                                                                </div>
                                                                <div class="field-permissions" style="margin-left: 8px; margin-top: 12px;">
                                                                    <label style="display: flex; align-items: center; font-size: 11px; color: rgba(255,255,255,0.9); cursor: pointer;">
                                                                        <input type="checkbox" name="options[client_tp_show]" style="margin-right: 4px; accent-color: white;">
                                                                        Show TP
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">TP Name</small>
                                                            <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 12px 0; color: white;">John Doe</h3>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Financial Information Grid -->
                                                    <div style="flex: 2; display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Balance</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #28a745;">$ 5,250.75</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">PnL</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #28a745;">$ 125.500</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Total Deposits</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: white;">$ 5,000.00</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Credit</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: white;">$ 0.00</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Bonus</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: white;">$ 250.75</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Equity</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #28a745;">$ 5,376.25</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Used Margin</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #28a745;">$ 1,250.00</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Free Margin</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #28a745;">$ 4,126.25</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Total Withdrawals</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: white;">$ 0.00</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Net Deposits</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: white;">$ 5,000.00</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="client-preview-container" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); max-height: 800px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #cbd5e0 #f7fafc;">
                                            <h5 style="margin-bottom: 20px; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                                                Client Information Preview
                                                <small style="color: #718096; font-size: 12px; display: block; margin-top: 5px;">Configure field-level permissions for client data</small>
                                            </h5>
                                            
                                            <!-- Personal Information Section -->
                                            <div class="field-section">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Personal Information</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                                                    
                                                    <!-- First Name -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Name</label>
                                                            <input type="text" value="John" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_name_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_name_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Last Name</label>
                                                            <input type="text" value="Doe" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_last_name_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_last_name_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Email</label>
                                                            <input type="email" value="john.doe@example.com" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_email_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_email_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Phone 1 -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Primary Phone</label>
                                                            <input type="tel" value="+1 234 567 8900" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_phone1_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_phone1_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Phone 2 -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Secondary Phone</label>
                                                            <input type="tel" value="+1 234 567 8901" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_phone2_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_phone2_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Country -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Country</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>United States</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_country_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_country_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sales Information Section -->
                                            <div class="field-section" style="margin-top: 24px;">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Sales Information</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                                                    
                                                    <!-- Sales Status -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Sales Status</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>Hot Lead</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_sales_status_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_sales_status_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Assigned User -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Assigned User</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>Sarah Johnson</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_user_id_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_user_id_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Account Type -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Account Type</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>Real</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_account_type_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_account_type_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- FTD Status -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">FTD Status</label>
                                                            <div style="display: flex; align-items: center;">
                                                                <input type="checkbox" checked disabled style="margin-right: 8px;">
                                                                <span style="font-weight: 500; color: #2d3748;">First Time Deposit</span>
                                                            </div>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_is_ftd_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_is_ftd_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Enabled Status -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Enabled Status</label>
                                                            <div style="display: flex; align-items: center;">
                                                                <input type="checkbox" checked disabled style="margin-right: 8px;">
                                                                <span style="font-weight: 500; color: #2d3748;">Enabled</span>
                                                            </div>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_enabled_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_enabled_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Username -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Username</label>
                                                            <input type="text" value="johndoe2024" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_username_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_username_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Password -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Password</label>
                                                            <input type="password" value="********" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_password_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_password_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- FTD Amount -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">FTD Amount</label>
                                                            <input type="text" value="$500.00" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_ftd_amount_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_ftd_amount_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Asset Group -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Asset Group</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>Premium Assets</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_asset_group_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_asset_group_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- First Owner -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Owner</label>
                                                            <input type="text" value="Michael Smith" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_owner_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_owner_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Team -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Team</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>Sales Team Alpha</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_team_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_team_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Last Deposit Amount -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Last Deposit Amount</label>
                                                            <input type="text" value="$750.00" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_last_deposit_amount_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_last_deposit_amount_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- First Comment Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Comment Date</label>
                                                            <input type="datetime-local" value="2024-03-12T10:15" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_comment_date_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_comment_date_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- First Comment Owner -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Comment Owner</label>
                                                            <input type="text" value="Sarah Johnson" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_comment_owner_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_first_comment_owner_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Assigned Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Assigned Date</label>
                                                            <input type="datetime-local" value="2024-03-11T09:00" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_assigned_date_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_assigned_date_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- FTD Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">FTD Date</label>
                                                            <input type="date" value="2024-03-15" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_ftd_date_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_ftd_date_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Created Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created Date</label>
                                                            <input type="datetime-local" value="2024-03-10T14:30" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_at_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_at_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Modified Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Modified Date</label>
                                                            <input type="datetime-local" value="2024-03-16T16:45" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_modified_date_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_modified_date_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Registration Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Registration Date</label>
                                                            <input type="datetime-local" value="2024-03-14T11:20" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_reg_date_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_reg_date_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Created By -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created By</label>
                                                            <input type="text" value="admin" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_by_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_by_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Additional Information Section -->
                                            <div class="field-section" style="margin-top: 24px;">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Additional Information</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                                                    
                                                    <!-- Source -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Source</label>
                                                            <input type="text" value="Google Ads" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_source_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_source_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Campaign -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Campaign</label>
                                                            <input type="text" value="Summer 2024" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_campaign_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_campaign_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Age -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Age</label>
                                                            <input type="number" value="32" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_age_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_age_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Gender -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Gender</label>
                                                            <select style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" disabled>
                                                                <option>Male</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_gender_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_gender_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Created By -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created By</label>
                                                            <input type="text" value="admin" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_by_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_by_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Created Date -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created Date</label>
                                                            <input type="datetime-local" value="2024-03-10T14:30" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_at_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_created_at_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Age -->
                                                    <div class="field-item" style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                        <div style="flex: 1;">
                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Age</label>
                                                            <input type="text" value="35 years" style="border: none; background: transparent; font-weight: 500; color: #2d3748; padding: 0;" readonly>
                                                        </div>
                                                        <div class="field-permissions" style="display: flex; gap: 8px; margin-left: 12px;">
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_age_show]" style="margin-right: 4px;">
                                                                Show
                                                            </label>
                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                <input type="checkbox" name="options[client_age_edit]" style="margin-right: 4px;">
                                                                Edit
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Client Page Cards Structure -->
                                            <div class="field-section" style="margin-top: 24px;">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Client Page Cards</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
                                                    
                                                    <!-- Card 1: Main Information & Tabs -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            Information Card (Main)
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_info]">
                                                    </div>

                                                    <!-- Card 2: Comments & Communication -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Comments Card
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_comments_show]">
                                                    </div>

                                                    <!-- Card 3: Actions & Quick Info -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Actions & Quick Info Card
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_card_actions]">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Main Information Card Tabs -->
                                            <div class="field-section" style="margin-top: 24px;">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Information Card Tabs</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
                                                    
                                                    <!-- Information Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            Information Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_info]">
                                                    </div>

                                                    <!-- Opened Orders Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                            </svg>
                                                            Opened Orders Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_opened_orders]">
                                                    </div>

                                                    <!-- Closed Orders Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                                            </svg>
                                                            Closed Orders Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_closed_orders]">
                                                    </div>

                                                    <!-- Money Transaction Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Money Transaction Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_money_transaction]">
                                                    </div>

                                                    <!-- Actions Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Actions Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_actions]">
                                                    </div>

                                                    <!-- Money History Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                            </svg>
                                                            Money History Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_money_history]">
                                                    </div>

                                                    <!-- KYC Tab -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2.5 5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100-2 1 1 0 000 2zm-1 4a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                                            </svg>
                                                            KYC Tab
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_tab_kyc]">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Comments Card Permissions -->
                                            <div class="field-section" style="margin-top: 24px;">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Comments Card Permissions</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
                                                    
                                                    <!-- Show Comments -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Show Comments
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_comments_show]">
                                                    </div>

                                                    <!-- Add Comments Permission -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Add Comments
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_comments_add]">
                                                    </div>

                                                    <!-- Edit Comments Permission -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                            </svg>
                                                            Edit Comments
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_comments_edit]">
                                                    </div>

                                                    <!-- Delete Comments Permission -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v6a1 1 0 11-2 0V7zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V7z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Delete Comments
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_comments_delete]">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Client Page Actions Permissions -->
                                            <div class="field-section" style="margin-top: 24px;">
                                                <h6 style="color: #4a5568; margin-bottom: 15px; font-weight: 600;">Client Page Actions</h6>
                                                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
                                                    
                                                    <!-- Send Email Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                                            </svg>
                                                            Send Email
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_send_email]">
                                                    </div>

                                                    <!-- Create Money Transaction Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Create Money Transaction
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_create_money_transaction]">
                                                    </div>

                                                    <!-- Create Request Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Create Request
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_create_request]">
                                                    </div>

                                                    <!-- Open Order Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Open Order
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_open_order]">
                                                    </div>

                                                    <!-- Export Data Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Export Data
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_export_data]">
                                                    </div>

                                                    <!-- View Requests Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                            </svg>
                                                            View Requests
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_view_requests]">
                                                    </div>

                                                    <!-- Login As Client Action -->
                                                    <div class="action-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                        <div class="action-label" style="display: flex; align-items: center; font-size: 14px; font-weight: 500; color: #4a5568;">
                                                            <svg class="action-icon" style="width: 16px; height: 16px; margin-right: 8px; color: #718096;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Login As Client
                                                        </div>
                                                        <input type="checkbox" class="custom-checkbox" name="options[client_action_login_as_client]">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Client Page Preview -->
                                            <div class="field-section" style="margin-top: 32px;">
                                                <h6 style="color: #4a5568; margin-bottom: 20px; font-weight: 600; font-size: 16px;">Client Page Preview</h6>
                                                
                                                <!-- Client Page Layout -->
                                                <div style="display: grid; grid-template-columns: 1fr 300px; gap: 20px; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #f8f9fa;">
                                                    <!-- Main Content Area (for tabs) -->
                                                    <div style="background: white; padding: 20px;">
                                                        <!-- Tab Navigation -->
                                                        <div style="border-bottom: 1px solid #e2e8f0; margin-bottom: 20px;">
                                                            <div style="display: flex; gap: 20px; overflow-x: auto;">
                                                                <div style="padding: 10px 16px; border-bottom: 2px solid #0d6efd; color: #0d6efd; font-weight: 500; cursor: pointer; white-space: nowrap;">Information</div>
                                                                <div style="padding: 10px 16px; color: #718096; cursor: pointer; white-space: nowrap;">Opened Orders</div>
                                                                <div style="padding: 10px 16px; color: #718096; cursor: pointer; white-space: nowrap;">Closed Orders</div>
                                                                <div style="padding: 10px 16px; color: #718096; cursor: pointer; white-space: nowrap;">Money Transaction</div>
                                                                <div style="padding: 10px 16px; color: #718096; cursor: pointer; white-space: nowrap;">Actions</div>
                                                                <div style="padding: 10px 16px; color: #718096; cursor: pointer; white-space: nowrap;">Money History</div>
                                                                <div style="padding: 10px 16px; color: #718096; cursor: pointer; white-space: nowrap;">KYC</div>
                                                            </div>
                                                        </div>
                                                        <!-- Tab Content Preview -->
                                                        <div style="color: #718096; text-align: center; padding: 40px 20px;">
                                                            <svg style="width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <p style="margin: 0; font-style: italic;">Tab content visibility controlled by permissions above</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Sidebar -->
                                                    <div style="background: #f8f9fa; padding: 16px; display: flex; flex-direction: column; gap: 16px;">
                                                        <!-- Comments Section -->
                                                        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
                                                            <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; background: #f8f9fa;">
                                                                <h6 style="margin: 0; font-weight: 600; color: #2d3748; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Comments
                                                                </h6>
                                                            </div>
                                                            <div style="max-height: 200px; overflow-y: auto;">
                                                                <!-- Sample Comments -->
                                                                <div style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px;">
                                                                    <div style="display: flex; justify-content: between; margin-bottom: 4px;">
                                                                        <span style="font-weight: 500; color: #2d3748;">Sarah Johnson</span>
                                                                        <span style="color: #718096; font-size: 11px;">2 hours ago</span>
                                                                    </div>
                                                                    <p style="margin: 0; color: #4a5568; line-height: 1.4;">Client requested additional verification documents.</p>
                                                                </div>
                                                                <div style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px;">
                                                                    <div style="display: flex; justify-content: between; margin-bottom: 4px;">
                                                                        <span style="font-weight: 500; color: #2d3748;">Michael Smith</span>
                                                                        <span style="color: #718096; font-size: 11px;">1 day ago</span>
                                                                    </div>
                                                                    <p style="margin: 0; color: #4a5568; line-height: 1.4;">Follow up scheduled for next week.</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Support Chat -->
                                                        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
                                                            <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; background: #f8f9fa;">
                                                                <h6 style="margin: 0; font-weight: 600; color: #2d3748; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                                                    </svg>
                                                                    Support Chat
                                                                </h6>
                                                            </div>
                                                            <div style="max-height: 200px; overflow-y: auto; padding: 12px;">
                                                                <div style="margin-bottom: 12px;">
                                                                    <div style="background: #e3f2fd; padding: 8px 12px; border-radius: 12px 12px 12px 4px; margin-bottom: 4px; font-size: 13px;">
                                                                        <p style="margin: 0; color: #1565c0;">Hi, I need help with my account verification.</p>
                                                                    </div>
                                                                    <span style="font-size: 11px; color: #718096;">John Doe - 10:30 AM</span>
                                                                </div>
                                                                <div style="margin-bottom: 12px;">
                                                                    <div style="background: #f5f5f5; padding: 8px 12px; border-radius: 12px 12px 4px 12px; margin-bottom: 4px; font-size: 13px;">
                                                                        <p style="margin: 0; color: #2d3748;">Hello John! I'll be happy to help you with that. What specific documents do you need assistance with?</p>
                                                                    </div>
                                                                    <span style="font-size: 11px; color: #718096;">Support Agent - 10:32 AM</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Actions Card -->
                                                        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
                                                            <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; background: #f8f9fa;">
                                                                <h6 style="margin: 0; font-weight: 600; color: #2d3748; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Actions
                                                                </h6>
                                                            </div>
                                                            <div style="padding: 12px;">
                                                                <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f0f0f0;">
                                                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                                                        <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                                                                        <span style="font-size: 13px; color: #059669; font-weight: 500;">Online</span>
                                                                    </div>
                                                                    <p style="margin: 0; font-size: 12px; color: #718096;">Last seen: 5 minutes ago</p>
                                                                </div>
                                                                
                                                                <div style="display: grid; gap: 8px;">
                                                                    <button style="background: #0d6efd; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">Send Email</button>
                                                                    <button style="background: #198754; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">Create Money Transaction</button>
                                                                    <button style="background: #ffc107; color: #000; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">Create Request</button>
                                                                    <button style="background: #6f42c1; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">Open Order</button>
                                                                    <button style="background: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">Export Data</button>
                                                                    <button style="background: #fd7e14; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; position: relative;">
                                                                        Requests
                                                                        <span style="position: absolute; top: -4px; right: -4px; background: #dc3545; color: white; border-radius: 50%; width: 16px; height: 16px; font-size: 10px; display: flex; align-items: center; justify-content: center;">3</span>
                                                                    </button>
                                                                    <button style="background: #20c997; color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">LogIn As Client</button>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
                    <div class="action-buttons">
                        <div class="btn-group">
                            <a href="{{ route('role.index') }}" class="btn btn-secondary">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                                Back to Roles
                            </a>
                        </div>
                        
                        <div class="btn-group">
                            @if ($role->getKey())
                                <button type="submit" formaction="{{route('role.clone',$role->getKey())}}" class="btn btn-info">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"/>
                                        <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    Clone Role
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                                    </svg>
                                    Update Role
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Create Role
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.multiple-select').select2({
                theme: 'bootstrap4',
                placeholder: 'Select options...',
                allowClear: true
            });

            // Expand/collapse
            $('.permission-header').on('click', function(e) {
                if ($(e.target).closest('.permission-toggle').length) { return; }
                const item = $(this).closest('.permission-item');
                item.toggleClass('expanded');
                const actions = item.find('.permission-actions');
                
                // Special handling for client page permissions with longer content
                const isClientPage = item.data('permission') === 'client_page';
                const animationDuration = isClientPage ? 500 : 300;
                
                if (item.hasClass('expanded')) {
                    actions.slideDown(animationDuration, function() {
                        // Scroll to the expanded section if it's the client page
                        if (isClientPage) {
                            $('html, body').animate({
                                scrollTop: item.offset().top - 100
                            }, 300);
                        }
                    });
                } else {
                    actions.slideUp(animationDuration);
                }
            });

            // Master toggle
            $('.permission-master-toggle').on('change', function() {
                const item = $(this).closest('.permission-item');
                const on = $(this).is(':checked');
                const isClientPage = item.data('permission') === 'client_page';
                const animationDuration = isClientPage ? 500 : 300;
                
                if (on) {
                    item.removeClass('disabled');
                    if (!item.hasClass('expanded')) { 
                        item.addClass('expanded'); 
                        item.find('.permission-actions').slideDown(animationDuration, function() {
                            if (isClientPage) {
                                $('html, body').animate({
                                    scrollTop: item.offset().top - 100
                                }, 300);
                            }
                        }); 
                    }
                } else {
                    item.addClass('disabled');
                    item.find('.custom-checkbox').prop('checked', false);
                    item.removeClass('expanded');
                    item.find('.permission-actions').slideUp(animationDuration);
                }
            });

            // Child checkbox -> master toggle
            $('.custom-checkbox').on('change', function() {
                const item = $(this).closest('.permission-item');
                const master = item.find('.permission-master-toggle');
                const checked = item.find('.custom-checkbox:checked').length > 0;
                master.prop('checked', checked);
                item.toggleClass('disabled', !checked);
            });

            // Validate
            $('#addform').on('submit', function(e) {
                const nameField = $('#name');
                if (!nameField.val().trim()) {
                    e.preventDefault();
                    nameField.addClass('is-invalid').focus();
                    showAlert('Please enter a role name', 'danger');
                    return false;
                } else {
                    nameField.removeClass('is-invalid');
                }
            });

            $('#name').on('input', function() {
                if ($(this).val().trim()) $(this).removeClass('is-invalid');
            });

            function showAlert(message, type) {
                const klass = type === 'danger' ? 'alert-danger' : 'alert-success';
                const html = `<div class="alert ${klass} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
                $('.container-fluid').prepend(html);
                setTimeout(() => { $('.alert').fadeOut(); }, 3000);
            }

            // Init states
            $('.permission-actions').hide();
            $('.permission-item').each(function() {
                const item = $(this);
                const checked = item.find('.custom-checkbox:checked').length > 0;
                item.toggleClass('disabled', !checked);
                item.find('.permission-master-toggle').prop('checked', checked);
                if (checked) { item.addClass('expanded'); item.find('.permission-actions').show(); }
            });

            // UI polish
            $('.custom-checkbox').on('change', function() {
                $(this).closest('.action-item').toggleClass('selected', $(this).is(':checked'));
            });
            $('.custom-checkbox:checked').each(function(){ $(this).closest('.action-item').addClass('selected'); });
            $('.action-item').hover(function(){ $(this).css('transform','translateY(-2px)'); }, function(){ $(this).css('transform','translateY(0)'); });
            $('.toggle-switch input').on('change', function(){ $(this).siblings('.toggle-slider').toggleClass('checked', $(this).is(':checked')); });
            $('.toggle-switch input:checked').each(function(){ $(this).siblings('.toggle-slider').addClass('checked'); });
        });
    </script>

    <style>
        .action-item.selected { border-color: #4299e1; background: #f0f8ff; }
        .toggle-slider.checked { background-color: #48bb78 !important; }
        .permission-item { transition: all 0.3s ease; }
        .permission-item.disabled .permission-header { opacity: 0.6; }
        .action-item { transition: all 0.3s ease; }
        .action-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .permission-actions { overflow: hidden; }
    </style>
@endsection

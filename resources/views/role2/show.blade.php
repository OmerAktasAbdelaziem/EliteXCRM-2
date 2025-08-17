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
                                            <!-- Client Page Preview -->
                                            <div class="field-section" style="margin-top: 32px;">
                                                <!-- Client Page Layout -->
                                                <div style="display: grid; grid-template-columns: 1000px 1fr; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #f8f9fa;">
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
                                                        <div style="padding: 20px;">
                                                            
                                                            <!-- Personal Information Section -->
                                                            <div style="margin-bottom: 32px;">
                                                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(45%, 1fr)); gap: 16px;">
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Name</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">John</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Last Name</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Doe</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Email</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">john.doe@example.com</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Primary Phone</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">+1 234 567 8900</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Secondary Phone</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">+1 234 567 8901</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Country</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">United States</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Sales Information Section -->
                                                            <div style="margin-bottom: 32px;">
                                                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(45%, 1fr)); gap: 16px;">
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Sales Status</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Hot Lead</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Assigned User</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Sarah Johnson</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Account Type</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Real</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">FTD Status</label>
                                                                            <span style="font-weight: 500; color: #10b981;">✓ First Time Deposit</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Enabled Status</label>
                                                                            <span style="font-weight: 500; color: #10b981;">✓ Enabled</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Username</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">johndoe2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Password</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">********</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">FTD Amount</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">$500.00</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Asset Group</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Premium Assets</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Owner</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Michael Smith</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Team</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Sales Team Alpha</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Last Deposit Amount</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">$750.00</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Comment Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 10, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Comment Owner</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Sarah Johnson</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Assigned Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 08, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">FTD Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 12, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 05, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Modified Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 16, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Registration Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 15, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created By</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">admin</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Additional Information Section -->
                                                            <div style="margin-bottom: 32px;">
                                                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(45%, 1fr)); gap: 16px;">
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Source</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Google Ads</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Campaign</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Summer 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Age</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">32</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Gender</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Male</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Created By</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">admin</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Registration Date</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Aug 15, 2024</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Chat Cards Sidebar -->
                                                    <div style="background: #f8f9fa; padding: 16px; display: flex; flex-direction: column; gap: 20px;">
                                                        <!-- Chat Cards -->
                                                        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 16px;">
                                                                <h4 style="margin: 0; color: #2d3748; font-weight: 600; font-size: 16px;">
                                                                    <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Chat Messages
                                                                </h4>
                                                                <span style="background: #e53e3e; color: white; border-radius: 10px; padding: 2px 8px; font-size: 12px; font-weight: 500;">3</span>
                                                            </div>
                                                            
                                                            <div style="space-y: 12px;">
                                                                <!-- Chat Message 1 -->
                                                                <div style="display: flex; gap: 12px; padding: 12px; background: #f7fafc; border-radius: 8px; margin-bottom: 12px;">
                                                                    <div style="width: 36px; height: 36px; background: #4299e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                                                        AJ
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                                            <span style="font-weight: 600; color: #2d3748; font-size: 14px;">Alex Johnson</span>
                                                                            <span style="font-size: 12px; color: #718096;">2 min ago</span>
                                                                        </div>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 13px; line-height: 1.4;">
                                                                            Hey team, I need help with the client onboarding process. Can someone review the new requirements?
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- Chat Message 2 -->
                                                                <div style="display: flex; gap: 12px; padding: 12px; background: #f7fafc; border-radius: 8px; margin-bottom: 12px;">
                                                                    <div style="width: 36px; height: 36px; background: #48bb78; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                                                        LM
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                                            <span style="font-weight: 600; color: #2d3748; font-size: 14px;">Lisa Martinez</span>
                                                                            <span style="font-size: 12px; color: #718096;">5 min ago</span>
                                                                        </div>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 13px; line-height: 1.4;">
                                                                            @Alex I'll take a look at it right now. Could you send me the updated checklist?
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- Chat Message 3 -->
                                                                <div style="display: flex; gap: 12px; padding: 12px; background: #f7fafc; border-radius: 8px; margin-bottom: 12px;">
                                                                    <div style="width: 36px; height: 36px; background: #ed8936; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                                                        DW
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                                            <span style="font-weight: 600; color: #2d3748; font-size: 14px;">David Wilson</span>
                                                                            <span style="font-size: 12px; color: #718096;">8 min ago</span>
                                                                        </div>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 13px; line-height: 1.4;">
                                                                            The client database has been updated. All pending tasks are now visible in the dashboard.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Chat Input -->
                                                            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                                                                <div style="display: flex; gap: 8px;">
                                                                    <input type="text" placeholder="Type your message..." style="flex: 1; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px;">
                                                                    <button style="background: #4299e1; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-size: 13px; cursor: pointer;">
                                                                        Send
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Support Chat Card -->
                                                        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 16px;">
                                                                <h4 style="margin: 0; color: #2d3748; font-weight: 600; font-size: 16px;">
                                                                    <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.894A1 1 0 0018 16V3z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Support Chat
                                                                </h4>
                                                                <div style="display: flex; align-items: center; gap: 6px;">
                                                                    <div style="width: 8px; height: 8px; background: #48bb78; border-radius: 50%;"></div>
                                                                    <span style="font-size: 12px; color: #48bb78; font-weight: 500;">Online</span>
                                                                </div>
                                                            </div>

                                                            <div style="space-y: 12px;">
                                                                <!-- Support Agent Message -->
                                                                <div style="display: flex; gap: 12px; padding: 12px; background: #edf2f7; border-radius: 8px; margin-bottom: 12px;">
                                                                    <div style="width: 36px; height: 36px; background: #6b46c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                                                        ST
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                                            <span style="font-weight: 600; color: #2d3748; font-size: 14px;">Support Team</span>
                                                                            <span style="background: #6b46c1; color: white; border-radius: 4px; padding: 1px 6px; font-size: 10px; font-weight: 500;">AGENT</span>
                                                                            <span style="font-size: 12px; color: #718096;">1 min ago</span>
                                                                        </div>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 13px; line-height: 1.4;">
                                                                            Hi! I'm here to help. How can I assist you with the CRM system today?
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- User Message -->
                                                                <div style="display: flex; gap: 12px; padding: 12px; background: #f0fff4; border-radius: 8px; border-left: 3px solid #48bb78; margin-bottom: 12px;">
                                                                    <div style="width: 36px; height: 36px; background: #718096; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                                                        YU
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                                            <span style="font-weight: 600; color: #2d3748; font-size: 14px;">You</span>
                                                                            <span style="font-size: 12px; color: #718096;">Just now</span>
                                                                        </div>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 13px; line-height: 1.4;">
                                                                            I need help understanding the role permissions system. Can you guide me through it?
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Support Chat Input -->
                                                            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                                                                <div style="display: flex; gap: 8px;">
                                                                    <input type="text" placeholder="Ask for help..." style="flex: 1; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px;">
                                                                    <button style="background: #6b46c1; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-size: 13px; cursor: pointer;">
                                                                        Send
                                                                    </button>
                                                                </div>
                                                                <p style="margin: 4px 0 0 0; font-size: 11px; color: #a0aec0;">
                                                                    Response time: ~2 minutes
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- Actions Card -->
                                                        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 16px;">
                                                                <h4 style="margin: 0; color: #2d3748; font-weight: 600; font-size: 16px;">
                                                                    <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Quick Actions
                                                                </h4>
                                                            </div>
                                                            
                                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                                                                <!-- Send Email Action -->
                                                                <button style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; color: #2d3748;">
                                                                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                                                    </svg>
                                                                    Send Email
                                                                </button>

                                                                <!-- Create Task Action -->
                                                                <button style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; color: #2d3748;">
                                                                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Create Task
                                                                </button>

                                                                <!-- Schedule Meeting Action -->
                                                                <button style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; color: #2d3748;">
                                                                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Schedule Meeting
                                                                </button>

                                                                <!-- Generate Report Action -->
                                                                <button style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; color: #2d3748;">
                                                                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Generate Report
                                                                </button>

                                                                <!-- Create Transaction Action -->
                                                                <button style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; color: #2d3748;">
                                                                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Money Transaction
                                                                </button>

                                                                <!-- Add Note Action -->
                                                                <button style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; color: #2d3748;">
                                                                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Add Note
                                                                </button>
                                                            </div>

                                                            <!-- Action Footer -->
                                                            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0; text-align: center;">
                                                                <p style="margin: 0; font-size: 12px; color: #a0aec0;">
                                                                    Quick actions for client management
                                                                </p>
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

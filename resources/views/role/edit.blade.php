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
        
        /* Alert Styles */
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffecb5;
            color: #856404;
        }
        
        .alert-warning .btn-close {
            filter: invert(1) grayscale(100%) brightness(100%);
        }
        
        /* List Permission Indicators */
        .action-item.list-permission {
            border-left: 4px solid #38b2ac;
            background: linear-gradient(90deg, #f0fdfa 0%, #ffffff 100%);
        }
        
        .action-item.dependent-permission {
            border-left: 4px solid #e2e8f0;
        }
        
        .action-item.dependent-permission.disabled-dependency {
            opacity: 0.6;
            background: #f8f9fa;
            border-left-color: #fbb6ce;
            cursor: not-allowed;
        }
        
        .action-item.dependent-permission.disabled-dependency .custom-checkbox {
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        /* Dependency System Styles */
        .list-permission {
            position: relative;
        }
        
        .dependent-permission {
            position: relative;
        }
        
        .dependent-permission.dependency-not-met {
            opacity: 0.4;
            pointer-events: none;
        }
        
        .dependent-permission.dependency-not-met .action-label {
            color: #a0aec0;
        }
        
        .dependent-permission.dependency-not-met input {
            cursor: not-allowed;
        }
        
        .dependency-tooltip::after {
            content: "⚠️ Requires list permission to be activated first";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1a202c;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }
        
        .dependency-tooltip.dependency-not-met:hover::after {
            opacity: 1;
        }
        
        .disabled-dependency {
            opacity: 0.5;
            pointer-events: none;
        }
        
        .disabled-dependency input {
            cursor: not-allowed;
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

                <form name="addform" id="addform" method="POST" action="{{ route('role.update',$role->getKey()) }}">
                    @csrf
                
                    <!-- Basic Information Section -->
                    <div class="modern-form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                Edit Role
                            </h3>
                            <p class="section-subtitle">
                                Modify role permissions and settings
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
                                            <h4>General</h4>
                                            <p>General Permissions</p>
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
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Settings
                                            </div>
                                         
                                            <input type="checkbox" class="custom-checkbox" name="roles[settings]" @roleHasPermission($role, 'settings') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Overview
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[overview]" @roleHasPermission($role, 'overview') checked @endroleHasPermission>
                                        </div>
                                        
                                        
                                        
                                        
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                            
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
                                        <div class="action-item list-permission">
                                            <div class="action-label">
                                                Leads List
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_list]" @roleHasPermission($role, 'leads_list') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Eye icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Leads
                                            </div>
                                            <input type="checkbox"  class="custom-checkbox" name="roles[leads_show]" @roleHasPermission($role, 'leads_show') checked @endroleHasPermission >
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Plus icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Leads
                                            </div>
                                           <input type="checkbox" class="custom-checkbox" name="roles[leads_create]" @roleHasPermission($role, 'leads_create') checked @endroleHasPermission >
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Plus icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Renew Leads
                                            </div>
                                           <input type="checkbox" class="custom-checkbox" name="roles[leads_renew]" @roleHasPermission($role, 'leads_renew') checked @endroleHasPermission >
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Pencil icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_edit]" @roleHasPermission($role, 'leads_edit') checked @endroleHasPermission >
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_delete]" @roleHasPermission($role, 'leads_delete') checked @endroleHasPermission  >
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Import Clients
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[import_clients]" @roleHasPermission($role, 'import_clients') checked @endroleHasPermission  >
                                        </div>
                                        
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Lead Actions
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_actions_actions]" @roleHasPermission($role, 'leads_actions_actions') checked @endroleHasPermission  >
                                        </div>
                                        
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Leads Cards Actions
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_cards_actions]" @roleHasPermission($role, 'leads_cards_actions') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Open Real
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_actions_open_real]" @roleHasPermission($role, 'leads_actions_open_real') checked @endroleHasPermission  >
                                        </div>
                                        
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Open Demo
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_actions_open_demo]" @roleHasPermission($role, 'leads_actions_open_demo') checked @endroleHasPermission  >
                                        </div>
                                        
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Show unassigned leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[show_unassigned_leads]" @roleHasPermission($role, 'show_unassigned_leads') checked @endroleHasPermission  >
                                        </div>
                                       
                                        
                                        <?php
                                        /*
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                New Clients Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_new_clients]" @roleHasPermission($role, 'leads_tabs_new_clients') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Call back Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_call_back]" @roleHasPermission($role, 'leads_tabs_call_back') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                No Answer Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_no_answer]" @roleHasPermission($role, 'leads_tabs_no_answer') checked @endroleHasPermission>
                                        </div>
                                       
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Not Now Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_not_now]" @roleHasPermission($role, 'leads_tabs_not_now') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Wrong Number Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_wrong_number]" @roleHasPermission($role, 'leads_tabs_wrong_number') checked @endroleHasPermission>
                                        </div>
                                         */
                                        ?>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                All Leads Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_all_leads]" @roleHasPermission($role, 'leads_tabs_all_leads') checked @endroleHasPermission>
                                        </div>
                                         <?php /*
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Call Back Later Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_call_back_later]" @roleHasPermission($role, 'leads_tabs_call_back_later') checked @endroleHasPermission>
                                        </div>
                                         */
                                        ?>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                My Leads Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_my_leads]" @roleHasPermission($role, 'leads_tabs_my_leads') checked @endroleHasPermission>
                                        </div>
                                        <?php /*
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                User Test Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_user_test]" @roleHasPermission($role, 'leads_tabs_user_test') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Not Interested Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_not_interested]" @roleHasPermission($role, 'leads_tabs_not_interested') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Dont Call Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_do_not_call]" @roleHasPermission($role, 'leads_tabs_do_not_call') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Follow up
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_follow_up]" @roleHasPermission($role, 'leads_tabs_follow_up') checked @endroleHasPermission>
                                        </div>
                                        */?>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                b2b
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_b2b]" @roleHasPermission($role, 'leads_tabs_b2b') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                New
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_new]" @roleHasPermission($role, 'leads_tabs_new') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Hot Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_hot]" @roleHasPermission($role, 'leads_tabs_hot') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Actions Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_actions]" @roleHasPermission($role, 'leads_tabs_actions') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- Trash icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                History Tab
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[leads_tabs_history]" @roleHasPermission($role, 'leads_tabs_history') checked @endroleHasPermission>
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
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Main TP Actions
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions]" @roleHasPermission($role, 'mainTp_actions') checked @endroleHasPermission>
                                        </div>
                                        
                                        
                                        
                                       
                                       
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Main TP Yes No
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[mainTp_yes_no]" @roleHasPermission($role, 'mainTp_yes_no') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Main TP Update Informations
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[mainTp_can_update]" @roleHasPermission($role, 'mainTp_can_update') checked @endroleHasPermission>
                                        </div>
                                      
                                        
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Main TP Money TRX Update
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[mainTp_money_trx_update]" @roleHasPermission($role, 'mainTp_money_trx_update') checked @endroleHasPermission>
                                        </div>
                                        
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Main TP Money TRX Delete
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[mainTp_money_trx_delete]" @roleHasPermission($role, 'mainTp_money_trx_delete') checked @endroleHasPermission>
                                        </div>
                                       
                                        
                                        <?php /*
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Refresh/Sync -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4v5h.582a7 7 0 11-1.06 7.032 1 1 0 011.415-1.415A5 5 0 1015 10h-1.5a1 1 0 010-2H17a1 1 0 011 1v5a1 1 0 11-2 0v-2.586A7 7 0 014 4z" clip-rule="evenodd"/>
                                                </svg>
                                                Main TP Cards Actions
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[mainTp_cards_actions]" @roleHasPermission($role, 'mainTp_cards_actions') checked @endroleHasPermission>
                                        </div>
                                        */ ?>
                                        
                                        
                                        
                                        
                                        
                                        
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
                                        <div class="action-item list-permission">
                                            <div class="action-label">
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Users List
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[users_list]" @roleHasPermission($role, 'users_list') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Users
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[users_show]" @roleHasPermission($role, 'users_show') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create User
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[users_create]" @roleHasPermission($role, 'users_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Edit User
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[users_edit]" @roleHasPermission($role, 'users_edit') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete User
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[users_delete]" @roleHasPermission($role, 'users_delete') checked @endroleHasPermission>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (UserPermission::isSuperAdmin(Auth::user()) ||  UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'roles_create') )
                            <!-- Pipeline Permission -->
                             <div class="permission-item" data-permission="user">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Pipelines</h4>
                                            <p>Access to pipelines management</p>
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
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Pipeline List</div><input type="checkbox" class="custom-checkbox" name="roles[pipeline_list]" @roleHasPermission($role, 'pipeline_list') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Pipeline View</div><input type="checkbox" class="custom-checkbox" name="roles[pipeline_view]" @roleHasPermission($role, 'pipeline_view') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Pipeline Create</div><input type="checkbox" class="custom-checkbox" name="roles[pipeline_create]" @roleHasPermission($role, 'pipeline_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Pipeline Update</div><input type="checkbox" class="custom-checkbox" name="roles[pipeline_edit]" @roleHasPermission($role, 'pipeline_edit') checked @endroleHasPermission></div>
                                    </div>
                                </div>
                            </div>
                           
                            <!-- Subscriptions Permission -->
                             <div class="permission-item" data-permission="user">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Subscriptions</h4>
                                            <p>Access to subscriptions management</p>
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
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Subscription List</div><input type="checkbox" class="custom-checkbox" name="roles[subscription_list]" @roleHasPermission($role, 'subscription_list') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Subscription View</div><input type="checkbox" class="custom-checkbox" name="roles[subscription_view]" @roleHasPermission($role, 'subscription_view') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Subscription Create</div><input type="checkbox" class="custom-checkbox" name="roles[subscription_create]" @roleHasPermission($role, 'subscription_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Subscription Update</div><input type="checkbox" class="custom-checkbox" name="roles[subscription_edit]" @roleHasPermission($role, 'subscription_edit') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Subscription Delete</div><input type="checkbox" class="custom-checkbox" name="roles[subscription_delete]" @roleHasPermission($role, 'subscription_delete') checked @endroleHasPermission></div>
                                    </div>
                                </div>
                            </div>
                            @endif
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
                                        <div class="action-item"><div class="action-label"><!-- Table icon --><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><rect width="18" height="4" x="1" y="3" rx="1"/><rect width="18" height="4" x="1" y="9" rx="1"/><rect width="18" height="4" x="1" y="15" rx="1"/></svg>Parts List</div><input type="checkbox" class="custom-checkbox" name="roles[parts_list]" @roleHasPermission($role, 'parts_list') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Parts</div><input type="checkbox" class="custom-checkbox" name="roles[parts_view]" @roleHasPermission($role, 'parts_view') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Parts</div><input type="checkbox" class="custom-checkbox" name="roles[parts_create]" @roleHasPermission($role, 'parts_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Parts</div><input type="checkbox" class="custom-checkbox" name="roles[parts_edit]" @roleHasPermission($role, 'parts_edit') checked @endroleHasPermission></div>
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
                                        <div class="action-item"><div class="action-label"><!-- Table icon --><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><rect width="18" height="4" x="1" y="3" rx="1"/><rect width="18" height="4" x="1" y="9" rx="1"/><rect width="18" height="4" x="1" y="15" rx="1"/></svg>Teams List</div><input type="checkbox" class="custom-checkbox" name="roles[teams_list]" @roleHasPermission($role, 'teams_list') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Teams</div><input type="checkbox" class="custom-checkbox" name="roles[teams_view]" @roleHasPermission($role, 'teams_view') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Team</div><input type="checkbox" class="custom-checkbox" name="roles[teams_create]" @roleHasPermission($role, 'teams_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Team</div><input type="checkbox" class="custom-checkbox" name="roles[teams_edit]" @roleHasPermission($role, 'teams_edit') checked @endroleHasPermission></div>
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
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>Reports List</div><input type="checkbox" class="custom-checkbox" name="roles[reports_list]" @roleHasPermission($role, 'reports_list') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Reports</div><input type="checkbox" class="custom-checkbox" name="roles[reports_view]" @roleHasPermission($role, 'reports_view') checked @endroleHasPermission></div>
                                        
                                        
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
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Retention</div><input type="checkbox" class="custom-checkbox" name="roles[retention_view]" @roleHasPermission($role, 'retention_view') checked @endroleHasPermission></div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                             <!-- Statistics Permission -->
                            <div class="permission-item" data-permission="retention">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Statistics</h4>
                                            <p>Access to statistics</p>
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
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Statistics</div><input type="checkbox" class="custom-checkbox" name="roles[statistics_view]" @roleHasPermission($role, 'statistics_view') checked @endroleHasPermission></div>
                                        
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
                                            <input type="checkbox" class="custom-checkbox" name="roles[requests_page_view]" @roleHasPermission($role, 'requests_page_view') checked @endroleHasPermission>
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
                                        <div class="action-item"><div class="action-label"><!-- Table icon --><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><rect width="18" height="4" x="1" y="3" rx="1"/><rect width="18" height="4" x="1" y="9" rx="1"/><rect width="18" height="4" x="1" y="15" rx="1"/></svg>Status List</div><input type="checkbox" class="custom-checkbox" name="roles[status_list]" @roleHasPermission($role, 'status_list') checked @endroleHasPermission></div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Status
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[status_view]" @roleHasPermission($role, 'status_view') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Status
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[status_create]" @roleHasPermission($role, 'status_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Status
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[status_edit]" @roleHasPermission($role, 'status_edit') checked @endroleHasPermission>
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
                                            <input type="checkbox" class="custom-checkbox" name="roles[status_delete]" @roleHasPermission($role, 'status_delete') checked @endroleHasPermission>
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
                                        <div class="action-item"><div class="action-label"><!-- Table icon --><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><rect width="18" height="4" x="1" y="3" rx="1"/><rect width="18" height="4" x="1" y="9" rx="1"/><rect width="18" height="4" x="1" y="15" rx="1"/></svg>Roles List</div><input type="checkbox" class="custom-checkbox" name="roles[roles_list]" @roleHasPermission($role, 'roles_list') checked @endroleHasPermission></div>
<?php /*                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Roles</div><input type="checkbox" class="custom-checkbox" name="roles[roles_view]" @roleHasPermission($role, 'roles_view') checked @endroleHasPermission></div>*/ ?>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Role</div><input type="checkbox" class="custom-checkbox" name="roles[roles_create]" @roleHasPermission($role, 'roles_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Role</div><input type="checkbox" class="custom-checkbox" name="roles[roles_edit]" @roleHasPermission($role, 'roles_edit') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Delete Role</div><input type="checkbox" class="custom-checkbox" name="roles[roles_delete]" @roleHasPermission($role, 'roles_delete') checked @endroleHasPermission></div>
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
                                        <div class="action-item list-permission">
                                            <div class="action-label">
                                                Emails Template List
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[emails_template_list]" @roleHasPermission($role, 'emails_template_list') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Emails Template Show
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[emails_template_show]" @roleHasPermission($role, 'emails_template_show') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Emails Template Update
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[emails_template_update]" @roleHasPermission($role, 'emails_template_update') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- Table icon -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <rect width="18" height="4" x="1" y="3" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="9" rx="1"/>
                                                    <rect width="18" height="4" x="1" y="15" rx="1"/>
                                                </svg>
                                                Emails Template Delete
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[emails_template_delete]" @roleHasPermission($role, 'emails_template_delete') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>View Emails</div><input type="checkbox" class="custom-checkbox" name="roles[emails_view]" @roleHasPermission($role, 'emails_view') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>Create Templates</div><input type="checkbox" class="custom-checkbox" name="roles[emails_template_create]" @roleHasPermission($role, 'emails_template_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>Edit Templates</div><input type="checkbox" class="custom-checkbox" name="roles[emails_template_edit]" @roleHasPermission($role, 'emails_template_edit') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Send Emails</div><input type="checkbox" class="custom-checkbox" name="roles[send_emails]" @roleHasPermission($role, 'send_emails') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails List</div><input type="checkbox" class="custom-checkbox" name="roles[emails_sender_email_list]" @roleHasPermission($role, 'emails_sender_email_list') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails Show</div><input type="checkbox" class="custom-checkbox" name="roles[sender_email_show]" @roleHasPermission($role, 'sender_email_show') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails</div><input type="checkbox" class="custom-checkbox" name="roles[emails_sender_emails]" @roleHasPermission($role, 'emails_sender_emails') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails Create</div><input type="checkbox" class="custom-checkbox" name="roles[emails_sender_emails_create]" @roleHasPermission($role, 'emails_sender_emails_create') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails Update</div><input type="checkbox" class="custom-checkbox" name="roles[emails_sender_emails_update]" @roleHasPermission($role, 'emails_sender_emails_update') checked @endroleHasPermission></div>
                                        <div class="action-item"><div class="action-label"><svg class="action-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Sender Emails Delete</div><input type="checkbox" class="custom-checkbox" name="roles[emails_sender_emails_delete]" @roleHasPermission($role, 'emails_sender_emails_delete') checked @endroleHasPermission></div>
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
                                        <div class="action-item list-permission"><div class="action-label">Banks List</div><input type="checkbox" class="custom-checkbox" name="roles[bank_list]" @roleHasPermission($role, 'bank_list') checked @endroleHasPermission></div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Banks
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[banks_view]" @roleHasPermission($role, 'banks_view') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Bank
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[banks_create]" @roleHasPermission($role, 'banks_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Bank
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[banks_edit]" @roleHasPermission($role, 'banks_edit') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Bank
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[banks_delete]" @roleHasPermission($role, 'banks_delete') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Edit Default USDT Address
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[edit_default_usdt_address]" @roleHasPermission($role, 'edit_default_usdt_address') checked @endroleHasPermission>
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
                                        <div class="action-item list-permission"><div class="action-label">Assets List</div><input type="checkbox" class="custom-checkbox" name="roles[assets_list]" @roleHasPermission($role, 'assets_list') checked @endroleHasPermission></div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Assets
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[assets_view]" @roleHasPermission($role, 'assets_view') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Asset
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[assets_create]" @roleHasPermission($role, 'assets_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Asset
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[assets_edit]" @roleHasPermission($role, 'assets_edit') checked @endroleHasPermission>
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
                                        <div class="action-item list-permission"><div class="action-label">Asset Groups List</div><input type="checkbox" class="custom-checkbox" name="roles[asset_groups_list]" @roleHasPermission($role, 'asset_groups_list') checked @endroleHasPermission></div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Eye -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Asset Groups
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[asset_groups_view]" @roleHasPermission($role, 'asset_groups_view') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Plus -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Asset Group
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[asset_groups_create]" @roleHasPermission($role, 'asset_groups_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Pencil/Edit -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Asset Group
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[asset_groups_edit]" @roleHasPermission($role, 'asset_groups_edit') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <!-- New icon: Trash/Delete -->
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Asset Group
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[asset_groups_delete]" @roleHasPermission($role, 'asset_groups_delete') checked @endroleHasPermission>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pipeline Management -->
                            <!--
                            <div class="permission-item" data-permission="pipeline">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                         
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
                                            
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Pipelines
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[pipeline_view]" @roleHasPermission($role, 'pipeline_view') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                              
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Pipeline
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[pipeline_create]" @roleHasPermission($role, 'pipeline_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                             
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Pipeline
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[pipeline_edit]" @roleHasPermission($role, 'pipeline_edit') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                              
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Pipeline
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[pipeline_delete]" @roleHasPermission($role, 'pipeline_delete') checked @endroleHasPermission>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

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
                                                        <div style="display: flex; align-items: center; gap: 12px;">
                                                            <div>
                                                                <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Name</small>
                                                                <h3 style="font-size: 20px; font-weight: 600; margin: 0; color: white;">John Doe</h3>
                                                            </div>
                                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                                <strong style="font-size: 12px; opacity: 0.9;">TP #12345</strong>
                                                                <div class="field-permissions">
                                                                    <label style="display: flex; align-items: center; font-size: 11px; color: rgba(255,255,255,0.9); cursor: pointer;">
                                                                        <input type="checkbox" name="roles[leads_main_tp]" @roleHasPermission($role, 'leads_main_tp') checked @endroleHasPermission style="margin-right: 4px; accent-color: white;">
                                                                        Show TP
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Financial Information Grid -->
                                                    <div style="flex: 2; display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Balance</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #00ff3a;">$ 5,250.75</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">PnL</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #00ff3a;">$ 125.500</h3>
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
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #00ff3a;">$ 5,376.25</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Used Margin</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #00ff3a;">$ 1,250.00</h3>
                                                        </div>
                                                        <div>
                                                            <small style="font-size: 12px; opacity: 0.9; display: block; margin-bottom: 2px;">Free Margin</small>
                                                            <h3 style="font-size: 16px; font-weight: 600; margin: 0; color: #00ff3a;">$ 4,126.25</h3>
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
                                                            <div style="margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                                                                <small style="color: #718096; font-size: 12px;">
                                                                    <svg style="width: 12px; height: 12px; margin-right: 4px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Click tabs to select multiple sections (multi-select enabled)
                                                                </small>
                                                                <div style="display: flex; gap: 8px;">
                                                                    <button type="button" class="btn-select-all" style="background: none; border: 1px solid #e2e8f0; color: #4299e1; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer;">Select All</button>
                                                                    <button type="button" class="btn-clear-all" style="background: none; border: 1px solid #e2e8f0; color: #718096; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer;">Clear All</button>
                                                                </div>
                                                            </div>
                                                            <div style="display: flex; gap: 20px; overflow-x: auto;" class="tab-navigation">
                                                                <div class="tab-item active" data-tab="information">Information</div>
                                                                <div class="tab-item" data-tab="opened-orders">Opened Orders</div>
                                                                <div class="tab-item" data-tab="closed-orders">Closed Orders</div>
                                                                <div class="tab-item" data-tab="money-transaction">Money Transaction</div>
                                                                <div class="tab-item" data-tab="actions">Actions</div>
                                                                <div class="tab-item" data-tab="money-history">Money History</div>
                                                                <div class="tab-item" data-tab="kyc">KYC</div>
                                                            </div>
                                                        </div>
                                                        <!-- Tab Content Preview -->
                                                        <div style="padding: 20px;">
                                                            
                                                            <!-- Personal Information Section -->
                                                            <div style="margin-bottom: 32px;">
                                                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(45%, 1fr)); gap: 16px;">
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Lead Id</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">12345678</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_lead_id_show]" @roleHasPermission($role, 'field_lead_id_show') checked @endroleHasPermission  style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                           
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">First Name</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">John</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_first_name_show]" @roleHasPermission($role, 'field_first_name_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_first_name_hide]" @roleHasPermission($role, 'field_first_name_hide') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Hide
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_first_name_edit]" @roleHasPermission($role, 'field_first_name_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_last_name_show]" @roleHasPermission($role, 'field_last_name_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_last_name_hide]" @roleHasPermission($role, 'field_last_name_hide') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Hide
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_last_name_edit]" @roleHasPermission($role, 'field_first_name_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_email_show]" @roleHasPermission($role, 'field_email_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_email_hide]" @roleHasPermission($role, 'field_email_hide') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Hide
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_email_edit]" @roleHasPermission($role, 'field_email_edit') checked @endroleHasPermission name="field_first_name_edit" @roleHasPermission($role, 'field_first_name_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_primary_phone_show]" @roleHasPermission($role, 'field_primary_phone_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_primary_phone_hide]" @roleHasPermission($role, 'field_primary_phone_hide') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Hide
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_primary_phone_edit]" @roleHasPermission($role, 'field_primary_phone_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_secondary_phone_show]" @roleHasPermission($role, 'field_secondary_phone_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_secondary_phone_hide]" @roleHasPermission($role, 'field_secondary_phone_hide') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Hide
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_secondary_phone_edit]" @roleHasPermission($role, 'field_secondary_phone_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_country_show]" @roleHasPermission($role, 'field_country_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_country_edit]" @roleHasPermission($role, 'field_country_edit') checked @endroleHasPermission  style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">USDT Address</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Usdt Adress</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_usdt_address_show]"  @roleHasPermission($role, 'field_usdt_address_show') checked @endroleHasPermission  style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_usdt_address_edit]"   @roleHasPermission($role, 'field_usdt_address_edit') checked @endroleHasPermission  style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Leads Leverage</label>
                                                                            <span style="font-weight: 500; color: #2d3748;">Leads Leverage</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_leads_leverage_show]"  @roleHasPermission($role, 'field_leads_leverage_show') checked @endroleHasPermission  style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_leads_leverage_edit]"   @roleHasPermission($role, 'field_leads_leverage_edit') checked @endroleHasPermission  style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_sales_status_show]" @roleHasPermission($role, 'field_sales_status_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_sales_status_edit]" @roleHasPermission($role, 'field_sales_status_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox"  name="roles[field_assigned_user_show]" @roleHasPermission($role, 'field_assigned_user_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_assigned_user_edit]" @roleHasPermission($role, 'field_assigned_user_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_account_type_show]" @roleHasPermission($role, 'field_account_type_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_account_type_edit]" @roleHasPermission($role, 'field_account_type_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_ftd_status_show]" @roleHasPermission($role, 'field_ftd_status_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_ftd_status_edit]" @roleHasPermission($role, 'field_ftd_status_edit') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div style="display: flex; align-items: center; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                                                                        <div style="flex: 1;">
                                                                            <label style="font-size: 12px; color: #718096; margin-bottom: 4px; display: block;">Enabled</label>
                                                                            <span style="font-weight: 500; color: #10b981;">Enabled</span>
                                                                        </div>
                                                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_leads_enabled_show]" @roleHasPermission($role, 'field_leads_enabled_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_enabled_status_show]" @roleHasPermission($role, 'field_enabled_status_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_enabled_status_edit]" @roleHasPermission($role, 'field_enabled_status_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_username_show]" @roleHasPermission($role, 'field_username_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_username_hide]" @roleHasPermission($role, 'field_username_hide') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Hide
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_username_edit]" @roleHasPermission($role, 'field_username_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_password_show]" @roleHasPermission($role, 'field_password_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox"  name="roles[field_password_edit]" @roleHasPermission($role, 'field_password_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_ftd_amount_show]" @roleHasPermission($role, 'field_ftd_amount_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_ftd_amount_edit]" @roleHasPermission($role, 'field_ftd_amount_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_asset_group_show]" @roleHasPermission($role, 'field_asset_group_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_asset_group_edit]" @roleHasPermission($role, 'field_asset_group_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_first_owner_show]" @roleHasPermission($role, 'field_first_owner_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_first_owner_edit]" @roleHasPermission($role, 'field_first_owner_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_team_show]" @roleHasPermission($role, 'field_team_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_team_edit]" @roleHasPermission($role, 'field_team_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_last_deposite_amount_show]" @roleHasPermission($role, 'field_last_deposite_amount_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_first_comment_date_show]" @roleHasPermission($role, 'field_first_comment_date_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_first_comment_owner_show]" @roleHasPermission($role, 'field_first_comment_owner_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_assigned_date_show]" @roleHasPermission($role, 'field_assigned_date_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_ftd_date_show]" @roleHasPermission($role, 'field_ftd_date_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_create_date_show]" @roleHasPermission($role, 'field_create_date_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_modified_date_show]" @roleHasPermission($role, 'field_modified_date_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_registration_date_show]" @roleHasPermission($role, 'field_registration_date_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_created_by_show]" @roleHasPermission($role, 'field_created_by_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
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
                                                                                <input type="checkbox" name="roles[field_source_show]" @roleHasPermission($role, 'field_source_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_source_edit]" @roleHasPermission($role, 'field_source_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_campaign_show]" @roleHasPermission($role, 'field_campaign_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_campaign_edit]" @roleHasPermission($role, 'field_campaign_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_age_show]" @roleHasPermission($role, 'field_age_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_age_edit]" @roleHasPermission($role, 'field_age_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                                                <input type="checkbox" name="roles[field_gender_show]" @roleHasPermission($role, 'field_gender_show') checked @endroleHasPermission style="margin-right: 4px;">
                                                                                Show
                                                                            </label>
                                                                            <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568;">
                                                                                <input type="checkbox" name="roles[field_gender_edit]" @roleHasPermission($role, 'field_gender_edit') checked @endroleHasPermission style="margin-right: 4px;">
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
                                                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                                                <h4 style="margin: 0; color: #2d3748; font-weight: 600; font-size: 16px;">
                                                                    <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Comments
                                                                </h4>
                                                                <div style="display: flex; gap: 12px; align-items: center;">
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[leads_cards_comments]" @roleHasPermission($role, 'leads_cards_comments') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Show Card
                                                                    </label>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[leads_add_comments]" @roleHasPermission($role, 'leads_add_comments') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Add
                                                                    </label>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[leads_edit_comments]" @roleHasPermission($role, 'leads_edit_comments') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Edit
                                                                    </label>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[leads_delete_comments]" @roleHasPermission($role, 'leads_delete_comments') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Delete
                                                                    </label>
                                                                </div>
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
                                                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                                                <h4 style="margin: 0; color: #2d3748; font-weight: 600; font-size: 16px;">
                                                                    <svg style="width: 18px; height: 18px; margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.894A1 1 0 0018 16V3z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Support Chat
                                                                </h4>
                                                                <div style="display: flex; gap: 12px; align-items: center;">
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_cards_chat]" @roleHasPermission($role, 'mainTp_cards_chat') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Show Card
                                                                    </label>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_add_chat]" @roleHasPermission($role, 'mainTp_add_chat') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Add
                                                                    </label>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_edit_chat]" @roleHasPermission($role, 'mainTp_edit_chat') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Edit
                                                                    </label>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_delete_chat]" @roleHasPermission($role, 'mainTp_delete_chat') checked @endroleHasPermission style="margin-right: 6px;">
                                                                        Delete
                                                                    </label>
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
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                                                        </svg>
                                                                        Send Email
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions_send_email]" @roleHasPermission($role, 'mainTp_actions_send_email') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>

                                                                <!-- Create Money Transaction Action -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Create Money Transaction
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions_create_money_transaction]" @roleHasPermission($role, 'mainTp_actions_create_money_transaction') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>

                                                                <!-- Create Request Action -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Create Request
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions_create_request]" @roleHasPermission($role, 'mainTp_actions_create_request') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>

                                                                <!-- Export Data Action -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Export Data
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[export_clients]" @roleHasPermission($role, 'export_clients') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>

                                                                <!-- Open Order Action -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Open Order
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions_open_order]" @roleHasPermission($role, 'mainTp_actions_open_order') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>

                                                                <!-- Requests Action -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Requests
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions_Requests]" @roleHasPermission($role, 'mainTp_actions_Requests') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>

                                                                <!-- Login As Client Action -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #2d3748;">
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Login As Client
                                                                    </div>
                                                                    <label style="display: flex; align-items: center; font-size: 12px; color: #4a5568; cursor: pointer;">
                                                                        <input type="checkbox" class="custom-checkbox" name="roles[mainTp_actions_login_as_client]" @roleHasPermission($role, 'mainTp_actions_login_as_client') checked @endroleHasPermission style="margin-right: 4px;">
                                                                        Show
                                                                    </label>
                                                                </div>
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

                            <!-- Ad Permissions -->
                            <div class="permission-item" data-permission="ads">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <!-- New icon: Folder Group -->
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Ad Management</h4>
                                            <p>Manage Ads</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="ads">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <svg class="expand-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="permission-actions">
                                    <div class="actions-grid">
                                        <div class="action-item list-permission">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                List Ads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[ads_list]" @roleHasPermission($role, 'ads_list') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Ads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[ads_view]" @roleHasPermission($role, 'ads_view') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Ad
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[ads_create]" @roleHasPermission($role, 'ads_create') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.9 9.9A2 2 0 004 14v2a2 2 0 002 2h2a2 2 0 001.414-.586l9.9-9.9a2 2 0 000-2.828l-2-2zM5 16v-2.586l9-9L16.586 7l-9 9H5z"/>
                                                </svg>
                                                Edit Ad
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[ads_edit]" @roleHasPermission($role, 'ads_edit') checked @endroleHasPermission>
                                        </div>
                                        <div class="action-item dependent-permission dependency-tooltip">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zm4 1a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M4 6a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm2-3a1 1 0 00-1 1v1h10V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Ad
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="roles[ads_delete]" @roleHasPermission($role, 'ads_delete') checked @endroleHasPermission>
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

            // Note: Main checkbox change handler is defined below as an enhanced document-level handler

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

            // Note: Visual feedback is handled by the main checkbox handler below
            $('.custom-checkbox:checked').each(function(){ $(this).closest('.action-item').addClass('selected'); });
            $('.action-item').hover(function(){ $(this).css('transform','translateY(-2px)'); }, function(){ $(this).css('transform','translateY(0)'); });
            $('.toggle-switch input').on('change', function(){ $(this).siblings('.toggle-slider').toggleClass('checked', $(this).is(':checked')); });
            $('.toggle-switch input:checked').each(function(){ $(this).siblings('.toggle-slider').addClass('checked'); });
            
            // Tab functionality - Multi-select
            $('.tab-item').on('click', function() {
                // Toggle active class on clicked tab (allows multiple selections)
                $(this).toggleClass('active');
                
                // Get selected tab name
                const tabName = $(this).data('tab');
                const isSelected = $(this).hasClass('active');
                
                // Get all selected tabs
                const selectedTabs = $('.tab-item.active').map(function() {
                    return $(this).data('tab');
                }).get();
                
                // Optional: You can add content switching logic here for multiple tabs
                // Show/hide content based on selected tabs
            });
            
            // Select All functionality
            $('.btn-select-all').on('click', function() {
                $('.tab-item').addClass('active');
            });
            
            // Clear All functionality
            $('.btn-clear-all').on('click', function() {
                $('.tab-item').removeClass('active');
            });
            
            // Function to uncheck all permissions that depend on a list
            function uncheckDependentPermissions(listPermissionName) {
                const dependencies = getDependencyMap();
                
                // Find all permissions that depend on this list
                Object.keys(dependencies).forEach(function(permissionName) {
                    if (dependencies[permissionName] === listPermissionName) {
                        $(`input[name="${permissionName}"]`).prop('checked', false);
                    }
                });
            }

            // Function to get the dependency map
            function getDependencyMap() {
                return {
                    // Leads Management Dependencies
                    'roles[leads_show]': 'roles[leads_list]',
                    'roles[leads_view]': 'roles[leads_list]',
                    'roles[leads_create]': 'roles[leads_list]',
                    'roles[leads_renew]': 'roles[leads_list]',
                    'roles[leads_edit]': 'roles[leads_list]',
                    'roles[leads_delete]': 'roles[leads_list]',
                    'roles[import_clients]': 'roles[leads_list]',
                    'roles[leads_actions_actions]': 'roles[leads_list]',
                    'roles[leads_cards_actions]': 'roles[leads_list]',
                    'roles[leads_actions_open_real]': 'roles[leads_list]',
                    'roles[leads_actions_open_demo]': 'roles[leads_list]',
                    'roles[show_unassigned_leads]': 'roles[leads_list]',
                    'roles[leads_import]': 'roles[leads_list]',
                    'roles[leads_export]': 'roles[leads_list]',
                    
                    // Users Management Dependencies
                    'roles[users_show]': 'roles[users_list]',
                    'roles[users_view]': 'roles[users_list]',
                    'roles[users_create]': 'roles[users_list]',
                    'roles[users_edit]': 'roles[users_list]',
                    'roles[users_delete]': 'roles[users_list]',
                    
                    // Email Dependencies
                    'roles[emails_template_show]': 'roles[emails_template_list]',
                    'roles[emails_template_update]': 'roles[emails_template_list]',
                    'roles[emails_template_delete]': 'roles[emails_template_list]',
                    'roles[emails_view]': 'roles[emails_template_list]',
                    'roles[emails_template_create]': 'roles[emails_template_list]',
                    'roles[emails_template_edit]': 'roles[emails_template_list]',
                    'roles[send_emails]': 'roles[emails_template_list]',
                    'roles[emails_sender_email_list]': 'roles[emails_template_list]',
                    'roles[sender_email_show]': 'roles[emails_template_list]',
                    'roles[emails_sender_emails_create]': 'roles[emails_template_list]',
                    'roles[emails_sender_emails_update]': 'roles[emails_template_list]',
                    'roles[emails_sender_emails_delete]': 'roles[emails_template_list]',
                    
                    // Banks Dependencies (Note: edit page uses bank_list instead of banks_list)
                    'roles[banks_view]': 'roles[bank_list]',
                    'roles[banks_create]': 'roles[bank_list]',
                    'roles[banks_edit]': 'roles[bank_list]',
                    'roles[banks_delete]': 'roles[bank_list]',
                    
                    // Assets Dependencies
                    'roles[assets_view]': 'roles[assets_list]',
                    'roles[assets_create]': 'roles[assets_list]',
                    'roles[assets_edit]': 'roles[assets_list]',
                    'roles[assets_delete]': 'roles[assets_list]',
                    
                    // Asset Groups Dependencies
                    'roles[asset_groups_view]': 'roles[asset_groups_list]',
                    'roles[asset_groups_create]': 'roles[asset_groups_list]',
                    'roles[asset_groups_edit]': 'roles[asset_groups_list]',
                    'roles[asset_groups_delete]': 'roles[asset_groups_list]',

                    
                    // Ads Dependencies
                    'roles[ads_view]': 'roles[ads_list]',
                    'roles[ads_create]': 'roles[ads_list]',
                    'roles[ads_edit]': 'roles[ads_list]',
                    'roles[ads_delete]': 'roles[ads_list]',
                    
                    // Pipeline Dependencies
                    'roles[pipeline_view]': 'roles[pipeline_list]',
                    'roles[pipeline_create]': 'roles[pipeline_list]',
                    'roles[pipeline_edit]': 'roles[pipeline_list]',
                    'roles[pipeline_delete]': 'roles[pipeline_list]'
                };
            }
            
            function getPermissionDisplayName(name) {
                const displayNames = {
                    // List permissions
                    'roles[leads_list]': 'Leads List',
                    'roles[users_list]': 'Users List',
                    'roles[emails_list]': 'Emails List',
                    'roles[emails_template_list]': 'Emails Template List',
                    'roles[bank_list]': 'Banks List',
                    'roles[assets_list]': 'Assets List',
                    'roles[asset_groups_list]': 'Asset Groups List',
                    'roles[pipeline_list]': 'Pipeline List',
                    
                    // Action permissions
                    'roles[leads_show]': 'View Leads',
                    'roles[leads_view]': 'View Leads',
                    'roles[leads_create]': 'Create Leads',
                    'roles[leads_renew]': 'Renew Leads',
                    'roles[leads_edit]': 'Edit Leads',
                    'roles[leads_delete]': 'Delete Leads',
                    'roles[import_clients]': 'Import Clients',
                    'roles[leads_actions_actions]': 'Lead Actions',
                    'roles[leads_cards_actions]': 'Leads Cards Actions',
                    'roles[leads_actions_open_real]': 'Open Real Account',
                    'roles[leads_actions_open_demo]': 'Open Demo Account',
                    'roles[show_unassigned_leads]': 'Show Unassigned Leads',
                    'roles[leads_import]': 'Import Leads',
                    'roles[leads_export]': 'Export Leads',
                    'roles[users_show]': 'View Users',
                    'roles[users_view]': 'View Users',
                    'roles[users_create]': 'Create Users',
                    'roles[users_edit]': 'Edit Users',
                    'roles[users_delete]': 'Delete Users',
                    'roles[emails_template_show]': 'View Email Template',
                    'roles[emails_template_update]': 'Update Email Template',
                    'roles[emails_template_delete]': 'Delete Email Template',
                    'roles[emails_view]': 'View Emails',
                    'roles[emails_template_create]': 'Create Email Template',
                    'roles[emails_template_edit]': 'Edit Email Template',
                    'roles[send_emails]': 'Send Emails',
                    'roles[emails_sender_email_list]': 'Sender Emails List',
                    'roles[sender_email_show]': 'View Sender Email',
                    'roles[emails_sender_emails_create]': 'Create Sender Email',
                    'roles[emails_sender_emails_update]': 'Update Sender Email',
                    'roles[emails_sender_emails_delete]': 'Delete Sender Email',
                    'roles[banks_view]': 'View Banks',
                    'roles[banks_create]': 'Create Banks',
                    'roles[banks_edit]': 'Edit Banks',
                    'roles[banks_delete]': 'Delete Banks',
                    'roles[assets_view]': 'View Assets',
                    'roles[assets_create]': 'Create Assets',
                    'roles[assets_edit]': 'Edit Assets',
                    'roles[assets_delete]': 'Delete Assets',
                    'roles[asset_groups_view]': 'View Asset Groups',
                    'roles[asset_groups_create]': 'Create Asset Groups',
                    'roles[asset_groups_edit]': 'Edit Asset Groups',
                    'roles[asset_groups_delete]': 'Delete Asset Groups',
                    'roles[pipeline_view]': 'View Pipeline',
                    'roles[pipeline_create]': 'Create Pipeline',
                    'roles[pipeline_edit]': 'Edit Pipeline',
                    'roles[pipeline_delete]': 'Delete Pipeline',
                    'roles[ads_view]': 'View Ads',
                    'roles[ads_create]': 'Create Ads',
                    'roles[ads_edit]': 'Edit Ads',
                    'roles[ads_delete]': 'Delete Ads',
                };
                return displayNames[name] || name.replace('roles[', '').replace(']', '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
            
            function updateDependencyStates() {
                const dependencies = getDependencyMap();
                
                Object.keys(dependencies).forEach(function(permissionName) {
                    const requiredPermission = dependencies[permissionName];
                    const $checkbox = $(`input[name="${permissionName}"]`);
                    const $requiredCheckbox = $(`input[name="${requiredPermission}"]`);
                    const $actionItem = $checkbox.closest('.action-item');
                    
                    if ($checkbox.length && $requiredCheckbox.length) {
                        const isRequiredActive = $requiredCheckbox.is(':checked');
                        
                        if (isRequiredActive) {
                            $actionItem.removeClass('disabled-dependency dependency-not-met');
                            $checkbox.prop('disabled', false);
                        } else {
                            $actionItem.addClass('disabled-dependency dependency-not-met');
                            $checkbox.prop('disabled', true);
                            if ($checkbox.is(':checked')) {
                                $checkbox.prop('checked', false);
                            }
                        }
                    }
                });
            }
            
            function showDependencyAlert(message, type) {
                showAlert(message, type);
            }
            
            function showAlert(message, type) {
                const klass = type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-success');
                const html = `<div class="alert ${klass} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
                $('.container-fluid').prepend(html);
                setTimeout(() => { $('.alert').fadeOut(); }, 5000);
            }
            
            // Function to check dependencies for permissions
            function checkDependencies($checkbox) {
                const name = $checkbox.attr('name');
                const dependencies = getDependencyMap();
                
                // Check if this permission has a dependency
                if (dependencies[name]) {
                    const requiredPermission = dependencies[name];
                    const $requiredCheckbox = $(`input[name="${requiredPermission}"]`);
                    
                    if ($requiredCheckbox.length && !$requiredCheckbox.is(':checked')) {
                        // Get human-readable names
                        const permissionName = getPermissionDisplayName(name);
                        const requiredName = getPermissionDisplayName(requiredPermission);
                        
                        const message = `⚠️ Cannot activate "${permissionName}" without first activating "${requiredName}". Please enable the list permission first.`;
                        
                        return {
                            valid: false,
                            message: message
                        };
                    }
                }
                
                return { valid: true };
            }
            
            // Function to uncheck all permissions that depend on a list
            function uncheckDependentPermissions(listPermissionName) {
                const dependencies = getDependencyMap();
                
                Object.keys(dependencies).forEach(function(permissionName) {
                    if (dependencies[permissionName] === listPermissionName) {
                        $(`input[name="${permissionName}"]`).prop('checked', false);
                    }
                });
            }
            
            // Function to apply visual indicators to permissions
            function applyPermissionIndicators() {
                const dependencies = getDependencyMap();
                
                $('.custom-checkbox').each(function() {
                    const $checkbox = $(this);
                    const name = $checkbox.attr('name');
                    const $actionItem = $checkbox.closest('.action-item');
                    
                    // Check if this is a list permission (appears as a dependency target)
                    const isListPermission = Object.values(dependencies).includes(name);
                    
                    // Check if this is a dependent permission
                    const isDependentPermission = dependencies.hasOwnProperty(name);
                    
                    if (isListPermission && !$actionItem.hasClass('list-permission')) {
                        $actionItem.addClass('list-permission');
                    }
                    
                    if (isDependentPermission && !$actionItem.hasClass('dependent-permission')) {
                        $actionItem.addClass('dependent-permission dependency-tooltip');
                    }
                });
                
                // Initial dependency state update
                updateDependencyStates();
            }

            // Initialize dependency system
            updateDependencyStates();
            
            // Apply visual indicators to permissions
            applyPermissionIndicators();

            // Main checkbox change handler with dependency checking and UI updates
            $(document).on('change', '.custom-checkbox', function() {
                const $checkbox = $(this);
                const item = $checkbox.closest('.permission-item');
                const master = item.find('.permission-master-toggle');
                
                // Check if this checkbox is being checked
                if ($checkbox.is(':checked')) {
                    // Check for dependencies
                    const dependencyResult = checkDependencies($checkbox);
                    if (!dependencyResult.valid) {
                        // Prevent checking and show message
                        $checkbox.prop('checked', false);
                        showDependencyAlert(dependencyResult.message, 'warning');
                        return;
                    }
                } else {
                    // If unchecking a list permission, uncheck all dependent permissions
                    const name = $checkbox.attr('name');
                    if (name && name.includes('_list]')) {
                        uncheckDependentPermissions(name);
                    }
                }
                
                // Update visual feedback
                $checkbox.closest('.action-item').toggleClass('selected', $checkbox.is(':checked'));
                
                const checked = item.find('.custom-checkbox:checked').length > 0;
                master.prop('checked', checked);
                item.toggleClass('disabled', !checked);
                
                // Update dependency states for all permissions
                updateDependencyStates();
            });
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
        
        /* Tab Styles */
        .tab-item {
            padding: 10px 16px;
            color: #718096;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
            position: relative;
            border-radius: 6px 6px 0 0;
        }
        
        .tab-item:hover {
            color: #4299e1;
            background-color: #f0f8ff;
            transform: translateY(-1px);
        }
        
        .tab-item.active {
            color: #0d6efd;
            font-weight: 500;
            border-bottom: 2px solid #0d6efd;
            background-color: #fff;
            box-shadow: 0 -2px 8px rgba(13, 110, 253, 0.1);
            position: relative;
        }
        
        .tab-item.active::before {
            content: '✓';
            position: absolute;
            top: 2px;
            right: 4px;
            font-size: 10px;
            color: #0d6efd;
            font-weight: bold;
        }
        
        .tab-item.active:hover {
            color: #0d6efd;
            background-color: #fff;
            transform: none;
        }
        
        .tab-navigation {
            position: relative;
        }
        
        .tab-navigation::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
            z-index: 1;
        }
        
        /* Button styles for Select All / Clear All */
        .btn-select-all:hover {
            background-color: #4299e1 !important;
            color: white !important;
            border-color: #4299e1 !important;
        }
        
        .btn-clear-all:hover {
            background-color: #718096 !important;
            color: white !important;
            border-color: #718096 !important;
        }
        
        .btn-select-all, .btn-clear-all {
            transition: all 0.3s ease;
        }
    </style>
@endsection

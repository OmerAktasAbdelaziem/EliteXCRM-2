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
            max-height: 300px;
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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name',$role->name) }}"
                                           placeholder="Enter role name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                       

                        <!-- User & System Assignment Section -->
                        <div class="modern-section">
                            <div class="modern-section-header">
                                <div class="section-icon">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="section-content">
                                    <h4>User & System Assignment</h4>
                                    <p>Assign users and system components to this role</p>
                                </div>
                            </div>
                            
                            <div class="modern-form-grid modern-form-grid-2">
                                <div class="modern-form-group">
                                    <label for="users" class="modern-label">Assign Users</label>
                                    <select class="multiple-select modern-select @error('users') is-invalid @enderror"

                                            id="users" name="users[]" multiple>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if (in_array($role->id, explode(',', trim($user->role_ids, '[]')))) selected @endif>
                                                {{ $user->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('users')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="parts">System Parts</label>
                                    <select class="form-control multiple-select @error('parts') is-invalid @enderror"
                                            id="parts" name="parts[]" multiple>
                                        @foreach ($parts as $part)
                                            <option value="{{ $part->id }}" @if($role->parts->contains($part->id)) selected @endif>
                                                {{ $part->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="modern-form-section">
                        <div class="section-header">
                            <h3 class="section-title">Role Permissions</h3>
                            <p class="section-subtitle">Configure what this role can access and modify</p>
                        </div>
                        
                        <div class="permissions-container">
                            <!-- Leads Management -->
                            <div class="permission-item" data-permission="leads">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Edit Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete Leads
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_delete]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Trading Platform -->
                            <div class="permission-item" data-permission="trading">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>Trading Platform</h4>
                                            <p>Access to trading platform and client trading management</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="trading">
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
                                                View Trading
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_main_tp]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Orders
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[trading_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Manage Orders
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[trading_manage]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                </svg>
                                                Demo Trading
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[leads_main_tp_demo]">
                                        </div>
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
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Emails
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[emails_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Templates
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[emails_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Edit Templates
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[emails_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                </svg>
                                                Sender Emails
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[emails_sender_emails]">
                                        </div>
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
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                View Reports
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[reports_view]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Create Reports
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[reports_create]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Edit Reports
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[reports_edit]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 4a1 1 0 000 2h8.905l-1.972 1.972a1 1 0 101.414 1.414L14.414 6H16a1 1 0 100-2H3zM3 10a1 1 0 100 2h5.905l-1.972 1.972a1 1 0 101.414 1.414L11.414 12H13a1 1 0 100-2H3zM9 16a1 1 0 01-1-1v-2a1 1 0 112 0v2a1 1 0 01-1 1z"/>
                                                </svg>
                                                Export Data
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[reports_export]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Administration -->
                            <div class="permission-item" data-permission="admin">
                                <div class="permission-header">
                                    <div class="permission-info">
                                        <div class="permission-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="permission-details">
                                            <h4>System Administration</h4>
                                            <p>System settings, user management, and admin functions</p>
                                        </div>
                                    </div>
                                    <div class="permission-toggle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" class="permission-master-toggle" data-target="admin">
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
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                User Management
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[admin_users]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15.586 13H14a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                System Settings
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[admin_settings]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Role Management
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[admin_roles]">
                                        </div>
                                        <div class="action-item">
                                            <div class="action-label">
                                                <svg class="action-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                System Logs
                                            </div>
                                            <input type="checkbox" class="custom-checkbox" name="options[admin_logs]">
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
@endsection
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

            // Permission item toggle functionality
            $('.permission-header').on('click', function(e) {
                // Don't trigger if clicking on toggle switch
                if ($(e.target).closest('.permission-toggle').length) {
                    return;
                }
                
                const permissionItem = $(this).closest('.permission-item');
                const isExpanded = permissionItem.hasClass('expanded');
                
                // Toggle expanded state
                permissionItem.toggleClass('expanded');
                
                // Animate the expansion
                const actions = permissionItem.find('.permission-actions');
                if (isExpanded) {
                    actions.slideUp(300);
                } else {
                    actions.slideDown(300);
                }
            });

            // Master toggle functionality
            $('.permission-master-toggle').on('change', function() {
                const permissionItem = $(this).closest('.permission-item');
                const isChecked = $(this).is(':checked');
                const target = $(this).data('target');
                
                // Toggle permission item state
                if (isChecked) {
                    permissionItem.removeClass('disabled');
                    // Auto-expand when enabled
                    if (!permissionItem.hasClass('expanded')) {
                        permissionItem.addClass('expanded');
                        permissionItem.find('.permission-actions').slideDown(300);
                    }
                } else {
                    permissionItem.addClass('disabled');
                    // Uncheck all sub-permissions
                    permissionItem.find('.custom-checkbox').prop('checked', false);
                    // Collapse when disabled
                    permissionItem.removeClass('expanded');
                    permissionItem.find('.permission-actions').slideUp(300);
                }
            });

            // Individual checkbox change
            $('.custom-checkbox').on('change', function() {
                const permissionItem = $(this).closest('.permission-item');
                const masterToggle = permissionItem.find('.permission-master-toggle');
                const allCheckboxes = permissionItem.find('.custom-checkbox');
                const checkedCheckboxes = permissionItem.find('.custom-checkbox:checked');
                
                // Update master toggle based on individual checkboxes
                if (checkedCheckboxes.length > 0) {
                    masterToggle.prop('checked', true);
                    permissionItem.removeClass('disabled');
                } else {
                    masterToggle.prop('checked', false);
                    permissionItem.addClass('disabled');
                }
            });

            // Form validation
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

            // Real-time validation
            $('#name').on('input', function() {
                if ($(this).val().trim()) {
                    $(this).removeClass('is-invalid');
                }
            });

            // Alert function
            function showAlert(message, type) {
                const alertClass = type === 'danger' ? 'alert-danger' : 'alert-success';
                const alert = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.container-fluid').prepend(alert);
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 3000);
            }

            // Initialize permission states based on existing data
            $('.permission-item').each(function() {
                const permissionItem = $(this);
                const checkedCheckboxes = permissionItem.find('.custom-checkbox:checked');
                const masterToggle = permissionItem.find('.permission-master-toggle');
                
                if (checkedCheckboxes.length > 0) {
                    masterToggle.prop('checked', true);
                    permissionItem.removeClass('disabled');
                } else {
                    masterToggle.prop('checked', false);
                    permissionItem.addClass('disabled');
                }
            });

            // Smooth animations for permission actions
            $('.permission-actions').hide();
            $('.permission-item.expanded .permission-actions').show();

            // Add hover effects
            $('.action-item').hover(
                function() {
                    $(this).css('transform', 'translateY(-2px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );

            // Custom checkbox styling enhancement
            $('.custom-checkbox').each(function() {
                if ($(this).is(':checked')) {
                    $(this).closest('.action-item').addClass('selected');
                }
            });

            $('.custom-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $(this).closest('.action-item').addClass('selected');
                } else {
                    $(this).closest('.action-item').removeClass('selected');
                }
            });

            // Toggle switch animation enhancement
            $('.toggle-switch input').on('change', function() {
                const slider = $(this).siblings('.toggle-slider');
                if ($(this).is(':checked')) {
                    slider.addClass('checked');
                } else {
                    slider.removeClass('checked');
                }
            });

            // Initialize toggle switches
            $('.toggle-switch input:checked').each(function() {
                $(this).siblings('.toggle-slider').addClass('checked');
            });
        });
    </script>

    <style>
        .action-item.selected {
            border-color: #4299e1;
            background: #f0f8ff;
        }
        
        .toggle-slider.checked {
            background-color: #48bb78 !important;
        }
        
        .permission-item {
            transition: all 0.3s ease;
        }
        
        .permission-item.disabled .permission-header {
            opacity: 0.6;
        }
        
        .action-item {
            transition: all 0.3s ease;
        }
        
        .action-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .permission-actions {
            overflow: hidden;
        }
    </style>
@endsection
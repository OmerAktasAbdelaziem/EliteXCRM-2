@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <style>
        /* Clean, Modern Design */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
        }

        .simple-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
        }

        /* Minimal Header */
        .simple-header {
            background: #6366f1;
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 12px 12px 0 0;
        }

        .simple-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
        }

        .simple-header p {
            margin: 0.25rem 0 0 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }

        /* Clean Form Controls */
        .form-control, .form-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.875rem;
            transition: border-color 0.2s;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Simple Sections */
        .form-section {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 0.5rem;
            color: #6366f1;
        }

        /* Clean Buttons */
        .btn-primary {
            background: #6366f1;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #4f46e5;
            transform: none;
        }

        .btn-outline-secondary {
            border: 1px solid #d1d5db;
            color: #6b7280;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-info {
            background: #06b6d4;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        .btn-info:hover {
            background: #0891b2;
        }

        /* Alert Styling */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        /* Responsive Grid */
        .responsive-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .responsive-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .responsive-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Select2 Clean Styling */
        .select2-container--bootstrap4 .select2-selection {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            min-height: 42px;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Error States */
        .is-invalid {
            border-color: #ef4444;
        }

        .text-danger {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Mobile Optimization */
        @media (max-width: 767px) {
            .simple-header {
                padding: 1rem 1.5rem;
            }

            .form-section {
                padding: 1rem 1.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .d-flex.gap-3 {
                flex-direction: column;
                gap: 0.5rem !important;
            }
        }

        /* Container Spacing */
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .container-fluid {
                padding: 2rem;
            }
        }

        /* Legacy Permission Styling (for sections not yet converted) */
        .border {
            border: 1px solid #e5e7eb !important;
            border-radius: 12px !important;
            transition: all 0.2s ease;
        }

        .border:hover {
            border-color: #6366f1 !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        .border .form-label {
            color: #6366f1;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .border .row .col {
            margin-bottom: 0.5rem;
        }

        .border .row .col input[type="checkbox"] {
            margin-left: 0.5rem;
            accent-color: #6366f1;
        }

        .position-absolute.badge {
            top: 1rem !important;
            right: 1rem !important;
        }

        .position-absolute.badge input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #6366f1;
        }

        /* Conditional Sections Styling */
        .permission-conditional-section {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .permission-conditional-section.collapsed {
            max-height: 0 !important;
            opacity: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            display: none !important;
        }

        .permission-conditional-section.expanded {
            max-height: none !important;
            opacity: 1 !important;
            margin-bottom: 1rem !important;
            display: block !important;
        }

        /* Child card styling for proper visual hierarchy */
        .permission-card.child-card {
            margin-left: 1.5rem;
            border-left: 3px solid #6366f1;
            background: #f8fafc;
        }

        .permission-card.conditional-child {
            margin-left: 1.5rem;
            margin-top: 0.75rem;
            border-left: 3px solid #6366f1;
            background: #f8fafc;
        }

        /* Enhanced legacy card child styling */
        .enhanced-legacy-card {
            margin-left: 1.5rem;
            margin-top: 0.75rem;
            border-left: 3px solid #6366f1;
            background: #f8fafc;
            padding: 1rem !important;
        }

        /* Fix text wrapping and spacing in legacy cards */
        .enhanced-legacy-card .row .col-2 {
            margin-bottom: 0.75rem;
            padding: 0.25rem;
            font-size: 0.8rem;
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .enhanced-legacy-card .row .col-2 input[type="checkbox"] {
            margin-left: 0.25rem;
            margin-right: 0.25rem;
            vertical-align: middle;
        }

        .enhanced-legacy-card .row .col-2 input[type="checkbox"].hide {
            margin-left: 0.25rem;
            margin-right: 0.25rem;
        }

        /* Ensure proper spacing for checkbox labels */
        .enhanced-legacy-card .col-2 {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        /* Better spacing for "Hide" checkboxes */
        .enhanced-legacy-card .col-2 .hide {
            margin-top: 0.25rem;
        }

        /* Responsive breakpoints for legacy cards */
        @media (max-width: 768px) {
            .enhanced-legacy-card .row .col-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .enhanced-legacy-card .row .col-2 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Force hide collapsed sections initially */
        .permission-conditional-section:not(.expanded) {
            display: none !important;
        }

        /* Permission item styling for better text layout */
        .permission-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
            padding: 0.25rem;
        }

        .permission-text {
            font-size: 0.8rem;
            color: #374151;
            font-weight: 500;
            word-wrap: break-word;
            overflow-wrap: break-word;
            margin-bottom: 0.25rem;
        }

        .permission-text.small {
            font-size: 0.7rem;
            color: #6b7280;
        }

        .enhanced-legacy-card .permission-item input[type="checkbox"] {
            margin: 0;
            align-self: flex-start;
        }
    </style>
@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bx bx-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('fail'))
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>
                        {{ session('fail') }}
                    </div>
                @endif

                <!-- Main Form -->
                <div class="simple-card">
                    <!-- Header -->
                    <div class="simple-header">
                        <h3>
                            @if ($role->getKey())
                                Edit Role
                            @else
                                Create Role
                            @endif
                        </h3>
                        <p>
                            @if ($role->getKey())
                                Modify role settings and permissions
                            @else
                                Set up new role with permissions
                            @endif
                        </p>
                    </div>

                    <!-- Form -->
                    <form name="addform" id="addform" method="POST" action="{{ $role->getKey()?route('role.update',$role->getKey()):route('role.store') }}">
                        @csrf
                        @if ($role->getKey())
                            @method('PUT')
                        @endif

                        <!-- Basic Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="bx bx-info-circle"></i>Basic Information
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Role Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name',$role->name) }}" placeholder="Enter role name" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Team Assignment -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="bx bx-group"></i>Team Assignment
                            </h4>
                            
                            <div class="responsive-grid">
                                <div>
                                    <label for="teams" class="form-label">Select Teams</label>
                                    <select class="multiple-select form-select" id="teams" name="teams[]" multiple>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}" @if($role->teams->contains($team->id)) selected @endif>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teams')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- User Assignment -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="bx bx-users"></i>User & System Assignment
                            </h4>
                            
                            <div class="responsive-grid">
                                <div>
                                    <label for="users" class="form-label">Assign Users</label>
                                    <select class="multiple-select form-select" id="users" name="users[]" multiple>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if (in_array($role->id, explode(',', trim($user->role_ids, '[]')))) selected @endif>
                                                {{ $user->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('users')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="parts" class="form-label">System Parts</label>
                                    <select class="multiple-select form-select" id="parts" name="parts[]" multiple>
                                        @foreach ($parts as $part)
                                            <option value="{{ $part->id }}" @if($role->parts->contains($part->id)) selected @endif>
                                                {{ $part->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parts')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="bx bx-shield-check"></i>Permissions
                            </h4>
                            @include("role.options",['role' => $role,'parts' => $parts,'teams' => $teams])
                        </div>

                        <!-- Actions -->
                        <div class="form-section">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{ route('role.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-1"></i>Back
                                </a>
                                
                                <div class="d-flex gap-3 flex-wrap">
                                    @if ($role->getKey())
                                        <button type="submit" formaction="{{route('role.clone',$role->getKey())}}" class="btn btn-info">
                                            <i class="bx bx-copy me-1"></i>Clone
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save me-1"></i>Update
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-plus me-1"></i>Create
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Simple Select2 initialization
            $('.multiple-select').select2({
                theme: 'bootstrap4',
                placeholder: 'Select options...',
                allowClear: true
            });

            // Simple form validation
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

            // Simple alert function
            function showAlert(message, type) {
                const alertClass = type === 'danger' ? 'alert-danger' : 'alert-success';
                const icon = type === 'danger' ? 'bx-error-circle' : 'bx-check-circle';
                
                const alert = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="bx ${icon} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                $('.container-fluid').prepend(alert);
                
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 3000);
            }

            // Enhanced permissions functionality
            
            // Handle select all functionality for both old and new permission designs
            $(document).on('change', '.check-all', function() {
                let container, checkboxes;
                
                // Check if it's the new permission card design
                const permissionCard = $(this).closest('.permission-card');
                const legacyCard = $(this).closest('.enhanced-legacy-card');
                
                if (permissionCard.length) {
                    container = permissionCard;
                    checkboxes = container.find('.permission-option input[type="checkbox"]');
                } else if (legacyCard.length) {
                    container = legacyCard;
                    checkboxes = container.find('input[type="checkbox"]:not(.check-all)');
                } else {
                    // Handle old design (col-md containers)
                    container = $(this).closest('.col-md-1, .col-md-4, .col-md-6, .col-md-7, .col-12');
                    checkboxes = container.find('input[type="checkbox"]:not(.check-all)');
                }
                
                const isChecked = $(this).is(':checked');
                checkboxes.prop('checked', isChecked);
                
                if (permissionCard.length) {
                    updatePermissionOptionStyles(permissionCard);
                }
                
                // Trigger auto-show functionality when select-all is used
                checkboxes.each(function() {
                    if ($(this).attr('data-col') || $(this).attr('name')) {
                        $(this).trigger('change');
                    }
                });
            });

            // Handle individual checkbox changes for both designs
            $(document).on('change', 'input[type="checkbox"]:not(.check-all)', function() {
                // Check if it's the new permission card design
                const permissionCard = $(this).closest('.permission-card');
                const legacyCard = $(this).closest('.enhanced-legacy-card');
                
                if (permissionCard.length) {
                    updatePermissionOptionStyles(permissionCard);
                    updateSelectAllState(permissionCard);
                } else if (legacyCard.length) {
                    updateLegacySelectAllState(legacyCard);
                } else {
                    // Handle old design
                    const container = $(this).closest('.col-md-1, .col-md-4, .col-md-6, .col-md-7, .col-12');
                    const allCheckboxes = container.find('input[type="checkbox"]:not(.check-all)');
                    const checkedCheckboxes = container.find('input[type="checkbox"]:not(.check-all):checked');
                    const selectAllCheckbox = container.find('.check-all');
                    
                    if (checkedCheckboxes.length === allCheckboxes.length && allCheckboxes.length > 0) {
                        selectAllCheckbox.prop('checked', true);
                    } else {
                        selectAllCheckbox.prop('checked', false);
                    }
                }
            });

            // Function to update visual styles based on checkbox state
            function updatePermissionOptionStyles(card) {
                card.find('.permission-option').each(function() {
                    const checkbox = $(this).find('input[type="checkbox"]');
                    if (checkbox.is(':checked')) {
                        $(this).addClass('checked');
                    } else {
                        $(this).removeClass('checked');
                    }
                });
            }

            // Function to update select all checkbox state for modern cards
            function updateSelectAllState(card) {
                const allCheckboxes = card.find('.permission-option input[type="checkbox"]');
                const checkedCheckboxes = card.find('.permission-option input[type="checkbox"]:checked');
                const selectAllCheckbox = card.find('.check-all');
                
                if (checkedCheckboxes.length === allCheckboxes.length && allCheckboxes.length > 0) {
                    selectAllCheckbox.prop('checked', true);
                } else {
                    selectAllCheckbox.prop('checked', false);
                }
            }

            // Function to update select all checkbox state for legacy cards
            function updateLegacySelectAllState(card) {
                const allCheckboxes = card.find('input[type="checkbox"]:not(.check-all)');
                const checkedCheckboxes = card.find('input[type="checkbox"]:not(.check-all):checked');
                const selectAllCheckbox = card.find('.check-all');
                
                if (checkedCheckboxes.length === allCheckboxes.length && allCheckboxes.length > 0) {
                    selectAllCheckbox.prop('checked', true);
                } else {
                    selectAllCheckbox.prop('checked', false);
                }
            }

            // Function to toggle conditional sections with animation
            function toggleConditionalSection(sectionClass, show) {
                const sections = $('.permission-conditional-section.' + sectionClass);
                
                if (show) {
                    sections.removeClass('collapsed').addClass('expanded');
                } else {
                    sections.removeClass('expanded').addClass('collapsed');
                }
            }

            // Auto-show functionality for main permissions (enhanced for bulk selection)
            $(document).on('change', 'input[data-col="sender_email"], input[name="options[emails_sender_emails]"]', function() {
                const isChecked = $('input[data-col="sender_email"]:checked, input[name="options[emails_sender_emails]"]:checked').length > 0;
                toggleConditionalSection('sender_email', isChecked);
            });

            $(document).on('change', 'input[data-col="leads_table"], input[name="options[leads_list]"]', function() {
                const isChecked = $('input[data-col="leads_table"]:checked, input[name="options[leads_list]"]:checked').length > 0;
                toggleConditionalSection('leads_table', isChecked);
            });

            $(document).on('change', 'input[data-col="leads"], input[name="options[leads_show]"]', function() {
                const isChecked = $('input[data-col="leads"]:checked, input[name="options[leads_show]"]:checked').length > 0;
                toggleConditionalSection('leads', isChecked);
            });

            $(document).on('change', 'input[data-col="main_tp"], input[name="options[leads_main_tp]"], input[name="options[leads_main_tp_demo]"]', function() {
                const anyMainTpChecked = $('input[data-col="main_tp"]:checked, input[name="options[leads_main_tp]"]:checked, input[name="options[leads_main_tp_demo]"]:checked').length > 0;
                toggleConditionalSection('main_tp', anyMainTpChecked);
            });

            $(document).on('change', 'input[data-col="leads_comments"], input[name="options[leads_cards_comments]"]', function() {
                const isChecked = $('input[data-col="leads_comments"]:checked, input[name="options[leads_cards_comments]"]:checked').length > 0;
                toggleConditionalSection('leads_comments', isChecked);
            });

            $(document).on('change', 'input[data-col="mainTp_comments"], input[name="options[mainTp_cards_comments]"]', function() {
                const isChecked = $('input[data-col="mainTp_comments"]:checked, input[name="options[mainTp_cards_comments]"]:checked').length > 0;
                toggleConditionalSection('mainTp_comments', isChecked);
            });

            $(document).on('change', 'input[data-col="mainTp_chat"], input[name="options[mainTp_cards_chat]"]', function() {
                const isChecked = $('input[data-col="mainTp_chat"]:checked, input[name="options[mainTp_cards_chat]"]:checked').length > 0;
                toggleConditionalSection('mainTp_chat', isChecked);
            });

            // Handle update_leads conditional display
            $(document).on('change', 'input[data-col="update_leads"], input[name="options[leads_can_update]"]', function() {
                if ($(this).is(':checked')) {
                    $('.update_leads').show();
                } else {
                    $('.update_leads').hide();
                }
            });

            // Handle update_mainTp conditional display
            $(document).on('change', 'input[data-col="update_mainTp"], input[name="options[mainTp_can_update]"]', function() {
                if ($(this).is(':checked')) {
                    $('.update_mainTp').show();
                } else {
                    $('.update_mainTp').hide();
                }
            });

            // Initialize conditional displays and auto-show functionality
            function initializeConditionalSections() {
                // First, hide ALL conditional sections by default
                $('.permission-conditional-section').addClass('collapsed').removeClass('expanded');
                
                // Then show only the ones that should be visible based on checked inputs
                
                // Initialize sender_email sections
                const senderEmailChecked = $('input[data-col="sender_email"]:checked, input[name="options[emails_sender_emails]"]:checked').length > 0;
                if (senderEmailChecked) {
                    $('.permission-conditional-section.sender_email').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize leads_table sections
                const leadsTableChecked = $('input[data-col="leads_table"]:checked, input[name="options[leads_list]"]:checked').length > 0;
                if (leadsTableChecked) {
                    $('.permission-conditional-section.leads_table').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize leads sections
                const leadsChecked = $('input[data-col="leads"]:checked, input[name="options[leads_show]"]:checked').length > 0;
                if (leadsChecked) {
                    $('.permission-conditional-section.leads').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize main_tp sections
                const mainTpChecked = $('input[data-col="main_tp"]:checked, input[name="options[leads_main_tp]"]:checked, input[name="options[leads_main_tp_demo]"]:checked').length > 0;
                if (mainTpChecked) {
                    $('.permission-conditional-section.main_tp').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize leads_comments sections
                const leadsCommentsChecked = $('input[data-col="leads_comments"]:checked, input[name="options[leads_cards_comments]"]:checked').length > 0;
                if (leadsCommentsChecked) {
                    $('.permission-conditional-section.leads_comments').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize mainTp_comments sections
                const mainTpCommentsChecked = $('input[data-col="mainTp_comments"]:checked, input[name="options[mainTp_cards_comments]"]:checked').length > 0;
                if (mainTpCommentsChecked) {
                    $('.permission-conditional-section.mainTp_comments').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize mainTp_chat sections
                const mainTpChatChecked = $('input[data-col="mainTp_chat"]:checked, input[name="options[mainTp_cards_chat]"]:checked').length > 0;
                if (mainTpChatChecked) {
                    $('.permission-conditional-section.mainTp_chat').removeClass('collapsed').addClass('expanded');
                }
                
                // Initialize update conditional displays
                if (!$('input[name="options[leads_can_update]"]').is(':checked')) {
                    $('.update_leads').hide();
                }
                
                if (!$('input[name="options[mainTp_can_update]"]').is(':checked')) {
                    $('.update_mainTp').hide();
                }
            }

            // Initialize on page load
            initializeConditionalSections();

            // Initialize styles on page load
            $('.permission-card').each(function() {
                updatePermissionOptionStyles($(this));
                updateSelectAllState($(this));
            });

            $('.enhanced-legacy-card').each(function() {
                updateLegacySelectAllState($(this));
            });

            // Handle conditional permissions (legacy support)
            $(document).on('change', 'input[data-col="sender_email"]', function() {
                if ($(this).is(':checked')) {
                    $('.sender_email').removeClass('d-none');
                } else {
                    $('.sender_email').addClass('d-none');
                }
            });

            // Handle leads table conditional display (legacy support)
            $(document).on('change', 'input[data-col="leads_table"], input[name="options[leads_list]"]', function() {
                if ($(this).is(':checked')) {
                    $('.leads_table').removeClass('d-none');
                } else {
                    $('.leads_table').addClass('d-none');
                }
            });

            // Handle main_tp conditional display (legacy support)
            $(document).on('change', 'input[data-col="main_tp"]', function() {
                if ($('input[data-col="main_tp"]:checked').length > 0) {
                    $('.main_tp').removeClass('d-none');
                } else {
                    $('.main_tp').addClass('d-none');
                }
            });

            // Handle leads conditional display (legacy support)
            $(document).on('change', 'input[data-col="leads"]', function() {
                if ($(this).is(':checked')) {
                    $('.leads').removeClass('d-none');
                } else {
                    $('.leads').addClass('d-none');
                }
            });
        });
    </script>
@endsection
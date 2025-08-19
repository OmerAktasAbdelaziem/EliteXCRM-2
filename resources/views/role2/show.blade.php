@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
@endsection
@section("wrapper")
    <div class="page-wrapper modern-bg">
        <div class="page-content">
            <div class="modern-container">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="modern-alert modern-alert-success">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('fail'))
                    <div class="modern-alert modern-alert-danger">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('fail') }}
                    </div>
                @endif

                <!-- Main Card -->
                <div class="modern-card">
                    <!-- Header -->
                    <div class="modern-card-header">
                        <div class="modern-header-content">
                            <h1 class="modern-title">
                                {{ isset($role) ? 'Edit Role' : 'Create Role' }}
                            </h1>
                            <p class="modern-subtitle">
                                {{ isset($role) ? 'Modify role permissions and settings' : 'Set up a new role with permissions' }}
                            </p>
                        </div>
                    </div>

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

                        <!-- Basic Information Section -->
                        <div class="modern-section">
                            <div class="modern-section-header">
                                <div class="section-icon">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="section-content">
                                    <h4>Basic Information</h4>
                                    <p>Define the role name and basic settings</p>
                                </div>
                            </div>
                            
                            <div class="modern-form-grid">
                                <div class="modern-form-group">
                                    <label for="name" class="modern-label">Role Name</label>
                                    <input type="text" class="modern-input @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name',$role->name) }}"
                                           placeholder="Enter role name" required>
                                    @error('name')
                                        <div class="error-message">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </div>
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
                                        <div class="error-message">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="modern-form-group">
                                    <label for="parts" class="modern-label">System Parts</label>
                                    <select class="multiple-select modern-select @error('parts') is-invalid @enderror"
                                            id="parts" name="parts[]" multiple>
                                        @foreach ($parts as $part)
                                            <option value="{{ $part->id }}" @if($role->parts->contains($part->id)) selected @endif>
                                                {{ $part->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parts')
                                        <div class="error-message">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Section -->
                        <div class="modern-section">
                            <div class="modern-section-header">
                                <div class="section-icon">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="section-content">
                                    <h4>Permissions</h4>
                                    <p>Configure role permissions and access levels</p>
                                </div>
                            </div>
                            @include("role.options",['role' => $role,'parts' => $parts,'teams' => $teams])
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-bar">
                            <div class="action-group">
                                <a href="{{ route('role.index') }}" class="modern-btn modern-btn-secondary">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Back to Roles
                                </a>
                            </div>
                            
                            <div class="action-group">
                                @if ($role->getKey())
                                    <button type="submit" formaction="{{route('role.clone',$role->getKey())}}" class="modern-btn modern-btn-info">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"/>
                                            <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        Clone Role
                                    </button>
                                    <button type="submit" class="modern-btn modern-btn-primary">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                                        </svg>
                                        Update Role
                                    </button>
                                @else
                                    <button type="submit" class="modern-btn modern-btn-primary">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
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
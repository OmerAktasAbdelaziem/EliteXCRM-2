@extends('layouts.app')

@section('style')
    <style>
        /* Clean Minimal Design - Primary Blue, White, Black Only */
        .clean-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }
        .clean-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        .filter-section {
            background: white;
            border-radius: 8px;
            color: black;
            border: 1px solid #dee2e6;
        }
        .user-selection-panel {
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            max-height: 400px;
            overflow-y: auto;
        }
        .selected-users-flexible-panel {
            background: white;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            min-height: 80px;
            max-height: none;
            overflow-y: auto;
            transition: all 0.3s ease;
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
        }
        .selected-users-flexible-panel .badge {
            flex-shrink: 0;
            max-width: calc(50% - 4px);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .selected-users-display-wrapper {
            min-height: 120px;
        }
        .user-selection-container {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        .user-selection-container:has(.collapse.show) {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .collapse-toggle-btn {
            transition: all 0.3s ease;
        }
        .collapse-toggle-btn i {
            transition: transform 0.3s ease;
        }
        .collapse-toggle-btn[aria-expanded="true"] i {
            transform: rotate(180deg);
        }
        .summary-card {
            background: #007bff;
            border: none;
            border-radius: 8px;
            color: white;
        }
        .stats-table {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        .stats-table thead th {
            background: #007bff;
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
        }
        .stats-table tbody tr:hover {
            background: #f8f9fa;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .action-btn {
            border-radius: 4px;
            padding: 6px 12px;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .filter-btn {
            background: #007bff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            color: white;
            font-weight: 600;
        }
        .filter-btn:hover {
            background: #0056b3;
            color: white;
        }
        .reset-btn {
            background: transparent;
            border: 1px solid white;
            border-radius: 4px;
            padding: 9px 19px;
            color: white;
            font-weight: 500;
        }
        .reset-btn:hover {
            background: white;
            color: #007bff;
        }
        /* Real-time notifications */
        .notification-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
        .live-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        /* Target tracking */
        .target-progress {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        .target-fill {
            height: 100%;
            background: #007bff;
            transition: width 0.3s ease;
        }
        .target-status {
            font-size: 0.75rem;
            font-weight: 600;
        }
        .target-achieved {
            color: #28a745;
        }
        .target-pending {
            color: #dc3545;
        }
        .checkbox-container {
            position: relative;
            padding-left: 30px;
            margin-bottom: 8px;
            cursor: pointer;
            font-size: 14px;
            user-select: none;
        }
        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .checkbox-container:hover input ~ .checkmark {
            background-color: #ccc;
        }
        .checkbox-container input:checked ~ .checkmark {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }
        .checkbox-container .checkmark:after {
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Modern UI Styles */
        .modern-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* User Avatar */
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        /* Modern Table Styles */
        .stats-table {
            border-radius: 15px;
            overflow: hidden;
        }

        .stats-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .stats-table tbody tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stats-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: scale(1.01);
        }

        .stats-table tbody td {
            padding: 20px 15px;
            vertical-align: middle;
            border: none;
        }

        /* Action Buttons */
        .action-btn {
            border-radius: 25px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Background Soft Colors */
        .bg-soft-primary { background: rgba(102, 126, 234, 0.1); }
        .bg-soft-warning { background: rgba(255, 193, 7, 0.1); }
        .bg-soft-danger { background: rgba(220, 53, 69, 0.1); }
        .bg-soft-info { background: rgba(13, 202, 240, 0.1); }
        .bg-soft-success { background: rgba(25, 135, 84, 0.1); }

        /* Comment Text Wrapping Styles */
        .comment-text {
            word-wrap: break-word;
            word-break: break-word;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            max-width: 100%;
            line-height: 1.4;
        }
        
        .comment-box {
            max-width: 100%;
            overflow: hidden;
        }
        
        .comment-container {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            overflow-wrap: break-word;
        }

        /* Client Selection Styles */
        .client-checkbox {
            transform: scale(1.2);
            margin: 0;
        }
        
        .client-checkbox:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        #selectAllClients {
            transform: scale(1.1);
        }
        
        #selectAllClients:indeterminate {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* Notification Styles */
        .notification-card {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .notification-card:hover {
            background: #e9ecef;
            transform: translateX(2px);
        }
        
        .notification-card.unread {
            border-left-color: #28a745;
            background: #d4edda;
        }
        
        .notification-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .notification-content {
            flex-grow: 1;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .notification-type-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        .latest-notification-preview {
            max-height: 60px;
            overflow: hidden;
            line-height: 1.3;
        }

        .badge-sm {
            font-size: 0.65rem;
            padding: 2px 5px;
        }

        .notification-type-badge.ms-1 {
            margin-left: 0.25rem !important;
        }



        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
            }
            
            .stats-table thead th {
                padding: 15px 10px;
                font-size: 0.75rem;
            }
            
            .stats-table tbody td {
                padding: 15px 10px;
            }
            
            .action-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
@endsection

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
<div class="container-fluid">

    <!-- Clean Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card clean-card filter-section">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="text-dark mb-0 me-3">Filters & Analytics</h4>
                        <span class="live-indicator me-2"></span>
                        <small class="text-muted">Live Updates Active</small>
                    </div>
                    <form method="GET" action="{{ route('user.stats') }}" class="row g-4">
                        <div class="col-md-2">
                            <label for="days" class="form-label text-dark fw-bold">Time Period</label>
                            <select name="days" id="days" class="form-select">
                                <option value="1" {{ $days == 1 ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ $days == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="3" {{ $days == 3 ? 'selected' : '' }}>Last 3 Days</option>
                                <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-dark fw-bold">Comments Filter</label>
                            <div class="row g-1">
                                <div class="col-6">
                                    <input type="number" name="comments_min" id="comments_min" class="form-control form-control-sm" 
                                           placeholder="Min" min="0" max="20" value="{{ request('comments_min', '') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="comments_max" id="comments_max" class="form-control form-control-sm" 
                                           placeholder="Max" min="0" max="20" value="{{ request('comments_max', '') }}">
                                </div>
                            </div>
                            <small class="text-muted">Filter by comment count range</small>
                            <div class="d-flex gap-1 mt-1">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCommentsFilter(0, 0)">0</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCommentsFilter(1, 1)">1</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCommentsFilter(2, 2)">2</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCommentsFilter(3, 3)">3</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCommentsFilter(4, null)">4+</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="user-selection-container">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-dark fw-bold mb-0">User Selection</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#userSelectionCollapse" aria-expanded="false" aria-controls="userSelectionCollapse">
                                        <i class="bx bx-chevron-down me-1"></i> Show Users
                                    </button>
                                </div>
                                
                                <!-- Collapsible User Selection Panel -->
                                <div class="collapse" id="userSelectionCollapse">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="user-selection-panel p-3 mb-3">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="all_users" {{ empty($selectedUserIds) ? 'checked' : '' }} onchange="toggleAllUsers()">
                                                    <label class="form-check-label fw-bold text-primary" for="all_users">
                                                        All Users
                                                    </label>
                                                </div>
                                                <hr class="my-3">
                                                <div class="row">
                                                    @foreach($allUsers as $user)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}" id="user_{{ $user->id }}"
                                                                       {{ in_array($user->id, $selectedUserIds ?? []) ? 'checked' : '' }}
                                                                       onchange="handleUserSelection({{ $user->id }}, '{{ $user->username }}', '{{ $user->first_name }} {{ $user->last_name }}')">
                                                                <label class="form-check-label" for="user_{{ $user->id }}">
                                                                    {{ $user->username }} ({{ $user->first_name }} {{ $user->last_name }})
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="text-center mb-3">
                                                <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="selectAllUsers()">
                                                    Select All
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="clearAllUsers()">
                                                    Clear All
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="collapse" data-bs-target="#userSelectionCollapse">
                                                    <i class="bx bx-check me-1"></i> Done
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dynamic Selected Users Display -->
                                <div class="selected-users-display-wrapper">
                                    <h6 class="text-muted mb-3">Selected Users:</h6>
                                    <div class="selected-users-flexible-panel p-3" id="selectedUsersDisplay">
                                        @if(empty($selectedUserIds))
                                            <div class="text-center py-3">
                                                <i class="bx bx-group text-muted" style="font-size: 1.5rem;"></i>
                                                <p class="text-muted mb-0 small">All Users Selected</p>
                                            </div>
                                        @else
                                            @foreach($selectedUserIds as $userId)
                                                @php $selectedUser = $allUsers->where('id', $userId)->first(); @endphp
                                                @if($selectedUser)
                                                    <div class="badge bg-primary me-1 mb-2 p-2" id="selected_{{ $userId }}">
                                                        {{ $selectedUser->username }}
                                                        <button type="button" class="btn-close btn-close-white ms-2" 
                                                                style="font-size: 0.7rem;" onclick="removeUser({{ $userId }})"></button>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Hidden inputs for form submission -->
                                <div id="hiddenInputs">
                                    @if(!empty($selectedUserIds))
                                        @foreach($selectedUserIds as $userId)
                                            <input type="hidden" name="user_ids[]" value="{{ $userId }}" id="hidden_{{ $userId }}">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="d-grid gap-2 w-100">
                                <button type="submit" class="filter-btn">
                                    Apply Filters
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                                    Reset All
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <div class="badge bg-light text-dark px-3 py-2">
                                Analysis Period: {{ $dateFrom->format('d/m/Y') }} - {{ $dateTo->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Summary Cards -->
    <div class="row mb-4">
        @if(!empty($selectedUserIds) || !empty($commentsMin) || !empty($commentsMax))
            <div class="col-12 mb-4">
                <div class="alert alert-info clean-card">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-info-circle me-3" style="font-size: 1.5rem; color: #007bff;"></i>
                        <div>
                            <strong>Active Filters:</strong> 
                            @if(!empty($selectedUserIds))
                                @if(count($selectedUserIds) === 1)
                                    Viewing data for user: <span class="badge bg-primary">{{ $allUsers->where('id', $selectedUserIds[0])->first()->username ?? 'Unknown' }}</span>
                                @else
                                    Analyzing {{ count($selectedUserIds) }} selected users:
                                    @foreach($selectedUserIds as $userId)
                                        <span class="badge bg-primary me-1">{{ $allUsers->where('id', $userId)->first()->username ?? 'Unknown' }}</span>
                                    @endforeach
                                @endif
                            @endif
                            @if(!empty($commentsMin) || !empty($commentsMax))
                                <span class="badge bg-warning me-1">
                                    Comments: 
                                    @if(!empty($commentsMin) && !empty($commentsMax))
                                        {{ $commentsMin }} - {{ $commentsMax }}
                                    @elseif(!empty($commentsMin))
                                        ≥ {{ $commentsMin }}
                                    @else
                                        ≤ {{ $commentsMax }}
                                    @endif
                                </span>
                            @endif
                            <a href="{{ route('user.stats', ['days' => $days]) }}" class="btn btn-sm btn-outline-primary ms-3">
                                Clear All Filters
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">{{ !empty($selectedUserIds) ? 'Selected Users' : 'Total Users' }}</h6>
                            <h2 class="text-white mb-0 fw-bold">{{ count($userStats) }}</h2>
                        </div>
                        <div class="text-white" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bx bx-group"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">New Clients</h6>
                            <h2 class="text-white mb-0 fw-bold">{{ collect($userStats)->sum('total_new_clients') }}</h2>
                        </div>
                        <div class="text-white" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bx bx-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Callbacks</h6>
                            <h2 class="text-white mb-0 fw-bold">{{ collect($userStats)->sum('total_callbacks') }}</h2>
                        </div>
                        <div class="text-white" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bx bx-phone-call"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">No Answers</h6>
                            <h2 class="text-white mb-0 fw-bold">{{ collect($userStats)->sum('total_no_answers') }}</h2>
                        </div>
                        <div class="text-white" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bx bx-phone-off"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Comments (Period)</h6>
                            <h2 class="text-white mb-0 fw-bold">{{ collect($userStats)->sum('total_comments_today') }}</h2>
                        </div>
                        <div class="text-white" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bx bx-message-dots"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card summary-card position-relative cursor-pointer" onclick="showNotificationsModal()" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="text-white-50 mb-2">
                                <i class="bx bx-bell me-1"></i>Latest Notification
                            </h6>
                            <div id="latestNotificationContent" class="text-white">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Loading...
                            </div>
                            <small class="text-white-50 mt-1 d-block" id="latestNotificationTime">Click to view all</small>
                        </div>
                        <div class="text-white" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bx bx-bell"></i>
                        </div>
                    </div>
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-light text-primary" id="notificationCount">0</span>
                    </div>
                    <!-- Hover effect -->
                    <div class="position-absolute bottom-0 start-0 end-0 text-center p-2" style="background: rgba(255,255,255,0.1); border-radius: 0 0 8px 8px; opacity: 0; transition: opacity 0.3s ease;" id="hoverIndicator">
                        <small class="text-white-50">Click to view all notifications</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean User Statistics Table with Daily Target Tracking -->
    <div class="row">
        <div class="col-12">
            <div class="card clean-card">
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table stats-table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>User Profile</th>
                                    <th class="text-center">New Status</th>
                                    <th class="text-center">Callbacks</th>
                                    <th class="text-center">No Answer Clients</th>
                                    <th class="text-center">Status Changes</th>
                                    <th class="text-center">Daily Target Progress</th>
                                    <th class="text-center">Comments Today</th>
                                    <th class="text-center">Target Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userStats as $stat)
                                    @php
                                        $noAnswerClients = $stat['total_no_answers'];
                                        $dailyTarget = $noAnswerClients * 3; // 3 comments per no answer client
                                        $commentsToday = $stat['total_comments_today'];
                                        $targetProgress = $dailyTarget > 0 ? round(($commentsToday / $dailyTarget) * 100, 1) : 0;
                                        $remaining = max(0, $dailyTarget - $commentsToday);
                                        $newClients = $stat['total_new_clients'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    {{ substr($stat['user']->first_name, 0, 1) }}{{ substr($stat['user']->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $stat['user']->username }}</h6>
                                                    <small class="text-muted">
                                                        {{ $stat['user']->first_name }} {{ $stat['user']->last_name }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <h5 class="mb-1 fw-bold text-success">{{ $newClients }}</h5>
                                                <small class="text-muted">New Clients</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <h5 class="mb-1 fw-bold text-warning">{{ $stat['total_callbacks'] }}</h5>
                                                @if($stat['comments_count_callback'] > 0)
                                                    <small class="text-success">
                                                        {{ $stat['comments_count_callback'] }} with comments
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <h5 class="mb-1 fw-bold text-dark">{{ $stat['total_no_answers'] }}</h5>
                                                @if($stat['comments_count_no_answer'] > 0)
                                                    <small class="text-success">
                                                        {{ $stat['comments_count_no_answer'] }} with comments
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusChanges = $stat['status_changed_clients'] ?? null;
                                                $totalChanged = $statusChanges ? $statusChanges['total_changed'] : 0;
                                                $noAnswerChanged = $statusChanges ? $statusChanges['no_answer_count'] : 0;
                                                $callbackChanged = $statusChanges ? $statusChanges['callback_count'] : 0;
                                            @endphp
                                            <div class="d-flex flex-column align-items-center">
                                                <h5 class="mb-1 fw-bold text-info">{{ $totalChanged }}</h5>
                                                @if($totalChanged > 0)
                                                    <div class="text-center">
                                                        @if($noAnswerChanged > 0)
                                                            <small class="text-muted d-block">
                                                                <i class="bx bx-phone-off me-1"></i>{{ $noAnswerChanged }} to No Answer
                                                            </small>
                                                        @endif
                                                        @if($callbackChanged > 0)
                                                            <small class="text-muted d-block">
                                                                <i class="bx bx-phone-call me-1"></i>{{ $callbackChanged }} to Callback
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <small class="text-muted">No changes</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $dailyTarget = $stat['total_no_answers'] * 3;
                                                $commentsToday = $stat['total_comments_today'];
                                                $targetProgress = $dailyTarget > 0 ? round(($commentsToday / $dailyTarget) * 100, 1) : 0;
                                                $remaining = max(0, $dailyTarget - $commentsToday);
                                            @endphp
                                            <div class="mb-2">
                                                <small class="fw-bold">Target: {{ $dailyTarget }} comments</small>
                                            </div>
                                            <div class="target-progress">
                                                <div class="target-fill" style="width: {{ min(100, $targetProgress) }}%;"></div>
                                            </div>
                                            <small class="target-status {{ $targetProgress >= 100 ? 'target-achieved' : 'target-pending' }}">
                                                {{ $targetProgress }}% Complete
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <h5 class="mb-0 fw-bold {{ $targetProgress >= 100 ? 'text-success' : 'text-warning' }}">
                                                {{ $commentsToday }}
                                            </h5>
                                            @if($remaining > 0)
                                                <small class="text-danger">{{ $remaining }} needed</small>
                                            @else
                                                <small class="text-success">Target achieved!</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($targetProgress >= 100)
                                                <span class="badge bg-success px-3 py-2">
                                                    Excellent
                                                </span>
                                            @elseif($targetProgress >= 75)
                                                <span class="badge bg-primary px-3 py-2">
                                                    Good
                                                </span>
                                            @elseif($targetProgress >= 50)
                                                <span class="badge bg-warning px-3 py-2">
                                                    Average
                                                </span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2">
                                                    Needs Work
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                @if($days == 1)
                                                    {{-- Current day - show live action buttons --}}
                                                    @if($stat['total_callbacks'] > 0)
                                                        <button type="button" class="action-btn btn btn-warning btn-sm"
                                                                onclick="showClientDetails({{ $stat['user']->id }}, 'Call Back', {{ $days }}, '{{ $stat['user']->username }}')">
                                                            Callbacks
                                                        </button>
                                                    @endif
                                                    @if($stat['total_no_answers'] > 0)
                                                        <button type="button" class="action-btn btn btn-primary btn-sm"
                                                                onclick="showClientDetails({{ $stat['user']->id }}, 'No Answer', {{ $days }}, '{{ $stat['user']->username }}')">
                                                            No Answer
                                                        </button>
                                                    @endif
                                                    @if($newClients > 0)
                                                        <button type="button" class="action-btn btn btn-success btn-sm"
                                                                onclick="showClientDetails({{ $stat['user']->id }}, 'New', {{ $days }}, '{{ $stat['user']->username }}')">
                                                            New Clients
                                                        </button>
                                                    @endif
                                                    @if($totalChanged > 0)
                                                        <button type="button" class="action-btn btn btn-info btn-sm"
                                                                onclick="showStatusChangedClients({{ $stat['user']->id }}, '{{ $stat['user']->username }}')">
                                                            Status Changes
                                                        </button>
                                                    @endif
                                                @else
                                                    {{-- Past periods - show report button --}}
                                                    <button type="button" class="action-btn btn btn-primary btn-sm"
                                                            onclick="showUserReport({{ $stat['user']->id }}, '{{ $stat['user']->username }}', '{{ $days }}', '{{ $dateFrom->format('Y-m-d') }}', '{{ $dateTo->format('Y-m-d') }}')">
                                                        <i class="bx bx-file-blank me-1"></i>View Report
                                                    </button>
                                                    
                                                    {{-- Show specific action buttons only if there's data --}}
                                                    @if($stat['total_callbacks'] > 0 || $stat['total_no_answers'] > 0 || $newClients > 0)
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @if($stat['total_callbacks'] > 0)
                                                                    <li><a class="dropdown-item" href="#" onclick="showUserReport({{ $stat['user']->id }}, '{{ $stat['user']->username }}', '{{ $days }}', '{{ $dateFrom->format('Y-m-d') }}', '{{ $dateTo->format('Y-m-d') }}', 'callbacks')">
                                                                        <i class="bx bx-phone-call me-2"></i>Callbacks Report
                                                                    </a></li>
                                                                @endif
                                                                @if($stat['total_no_answers'] > 0)
                                                                    <li><a class="dropdown-item" href="#" onclick="showUserReport({{ $stat['user']->id }}, '{{ $stat['user']->username }}', '{{ $days }}', '{{ $dateFrom->format('Y-m-d') }}', '{{ $dateTo->format('Y-m-d') }}', 'no_answer')">
                                                                        <i class="bx bx-phone-off me-2"></i>No Answer Report
                                                                    </a></li>
                                                                @endif
                                                                @if($newClients > 0)
                                                                    <li><a class="dropdown-item" href="#" onclick="showUserReport({{ $stat['user']->id }}, '{{ $stat['user']->username }}', '{{ $days }}', '{{ $dateFrom->format('Y-m-d') }}', '{{ $dateTo->format('Y-m-d') }}', 'new_clients')">
                                                                        <i class="bx bx-user-plus me-2"></i>New Clients Report
                                                                    </a></li>
                                                                @endif
                                                                @if($totalChanged > 0)
                                                                    <li><a class="dropdown-item" href="#" onclick="showUserReport({{ $stat['user']->id }}, '{{ $stat['user']->username }}', '{{ $days }}', '{{ $dateFrom->format('Y-m-d') }}', '{{ $dateTo->format('Y-m-d') }}', 'status_changes')">
                                                                        <i class="bx bx-refresh me-2"></i>Status Changes Report
                                                                    </a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bx bx-info-circle mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                                                <h5 class="text-muted">No User Statistics Available</h5>
                                                <p class="mb-0">No data found for the selected period and filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Details Modal -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientDetailsModalLabel">Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Comments Filter in Modal -->
                <div class="row mb-3" id="modalCommentsFilter" style="display: none;">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Comments Filter</label>
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <input type="number" id="modal_comments_min" class="form-control form-control-sm" 
                                                       placeholder="Min" min="0" max="20">
                                            </div>
                                            <div class="col-6">
                                                <input type="number" id="modal_comments_max" class="form-control form-control-sm" 
                                                       placeholder="Max" min="0" max="20">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setModalCommentsFilter(0, 0)">0</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setModalCommentsFilter(1, 1)">1</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setModalCommentsFilter(2, 2)">2</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setModalCommentsFilter(3, 3)">3</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setModalCommentsFilter(4, null)">4+</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyModalCommentsFilter()">Apply Filter</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearModalCommentsFilter()">Clear</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="modalFilterStatus" class="text-muted small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Change Filter in Modal -->
                <div class="row mb-3" id="statusChangeFilter" style="display: none;">
                    <div class="col-12">
                        <div class="card bg-primary bg-opacity-10 border-primary">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="bx bx-filter me-1"></i>Status Change Filter
                                        </label>
                                        <select id="statusChangeType" class="form-select form-select-sm">
                                            <option value="all">All New Client Status Changes</option>
                                            <option value="callback">New Client → Call Back Only</option>
                                            <option value="no_answer">New Client → No Answer Only</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="d-flex gap-2 align-items-center">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyStatusChangeFilter()">
                                                <i class="bx bx-filter me-1"></i>Apply Filter
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearStatusChangeFilter()">
                                                <i class="bx bx-x me-1"></i>Clear
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="statusChangeFilterStatus" class="text-muted small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Client Transfer Section -->
                <div class="row mb-3" id="clientTransferSection" style="display: none;">
                    <div class="col-12">
                        <div class="card bg-warning bg-opacity-10 border-warning">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="text-warning mb-2">
                                            <i class="bx bx-transfer me-2"></i>Transfer Selected Clients
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            <span id="selectedClientCount">0</span> client(s) selected
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Transfer to User:</label>
                                        <select class="form-select form-select-sm" id="transferToUser">
                                            <option value="">Select User...</option>
                                            @foreach($allUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->first_name }} {{ $user->last_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-warning btn-sm" onclick="transferSelectedClients()">
                                                <i class="bx bx-transfer me-1"></i>Transfer Clients
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearClientSelection()">
                                                <i class="bx bx-x me-1"></i>Clear Selection
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="clientDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">
                    <i class="bx bx-bell me-2"></i>Recent Notifications
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notificationsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="refreshNotifications()">
                    <i class="bx bx-refresh me-1"></i>Refresh
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/user-stats.js') }}"></script>
<script>
// Page-specific variables that need PHP data
window.csrfToken = '{{ csrf_token() }}';

// Additional functions that require PHP data
function showUserReport(userId, username, days, dateFrom, dateTo, filterType = null) {
    const modal = new bootstrap.Modal(document.getElementById('userReportModal'));
    
    const reportTitle = document.getElementById('userReportTitle');
    const reportContent = document.getElementById('userReportContent');
    
    let title = `${username} - Report`;
    if (days === 'yesterday') {
        title += ' (Yesterday)';
    } else if (days === '1') {
        title += ' (Today)';
    } else {
        title += ` (${days} days)`;
    }
    
    if (filterType) {
        title += ` - ${filterType.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}`;
    }
    
    reportTitle.textContent = title;
    reportContent.innerHTML = '<div class="text-center p-4"><i class="bx bx-loader-alt bx-spin" style="font-size: 2rem;"></i><p class="mt-2">Generating report...</p></div>';
    
    modal.show();
    
    const params = {
        user_id: userId,
        days: days,
        date_from: dateFrom,
        date_to: dateTo
    };
    
    if (filterType) {
        params.filter_type = filterType;
    }
    
    $.ajax({
        url: '{{ route("user.stats.report") }}',
        method: 'GET',
        data: params,
        success: function(response) {
            reportContent.innerHTML = response;
        },
        error: function() {
            reportContent.innerHTML = '<div class="alert alert-danger">Error generating report.</div>';
        }
    });
}

function showStatusChangedClients(userId, username) {
    const modal = new bootstrap.Modal(document.getElementById('clientDetailsModal'));
    const modalTitle = document.getElementById('clientDetailsModalLabel');
    const modalBody = document.querySelector('#clientDetailsModal .modal-body');
    
    modalTitle.textContent = `Status Changes - ${username}`;
    modalBody.innerHTML = '<div class="text-center p-4"><i class="bx bx-loader-alt bx-spin" style="font-size: 2rem;"></i><p class="mt-2">Loading status changes...</p></div>';
    
    modal.show();
    
    $.ajax({
        url: `/user-stats/status-changed-clients/${userId}`,
        method: 'GET',
        success: function(response) {
            modalBody.innerHTML = response;
        },
        error: function() {
            modalBody.innerHTML = '<div class="alert alert-danger">Error loading status changes.</div>';
        }
    });
}

function transferSelectedClients() {
    const selectedCheckboxes = document.querySelectorAll('.client-checkbox:checked');
    const transferToUserId = document.getElementById('transferToUser').value;
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one client to transfer.');
        return;
    }
    
    if (!transferToUserId) {
        alert('Please select a user to transfer clients to.');
        return;
    }
    
    const selectedClients = Array.from(selectedCheckboxes).map(checkbox => ({
        id: checkbox.getAttribute('data-client-id'),
        name: checkbox.getAttribute('data-client-name')
    }));
    
    const transferToUserSelect = document.getElementById('transferToUser');
    const targetUserName = transferToUserSelect.options[transferToUserSelect.selectedIndex].text;
    
    const clientNames = selectedClients.map(client => client.name).join(', ');
    const confirmMessage = `Are you sure you want to transfer ${selectedClients.length} client(s) to ${targetUserName}?\n\nClients: ${clientNames}`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    const transferBtn = document.querySelector('[onclick="transferSelectedClients()"]');
    const originalText = transferBtn.innerHTML;
    transferBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Transferring...';
    transferBtn.disabled = true;
    
    $.ajax({
        url: '/user-stats/transfer-clients',
        method: 'POST',
        data: {
            client_ids: selectedClients.map(client => client.id),
            target_user_id: transferToUserId,
            _token: window.csrfToken
        },
        success: function(response) {
            if (response.success) {
                showNotificationToast('Transfer Successful', 
                    `Successfully transferred ${response.transferred_count} client(s) to ${targetUserName}`, false);
                
                clearClientSelection();
                
                if (window.currentModalData) {
                    showClientDetails(
                        window.currentModalData.userId,
                        window.currentModalData.status,
                        window.currentModalData.days,
                        window.currentModalData.username
                    );
                }
                
                setTimeout(() => {
                    location.reload();
                }, 2000);
                
            } else {
                alert('Transfer failed: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr) {
            let errorMessage = 'Transfer failed. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            alert(errorMessage);
        },
        complete: function() {
            transferBtn.innerHTML = originalText;
            transferBtn.disabled = false;
        }
    });
}

// Modal cleanup
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('clientDetailsModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            if (typeof clearClientSelection === 'function') {
                clearClientSelection();
            }
        });
    }
    
    initializeRealTimeUpdates();
    requestNotificationPermission();
    initializeUserSelectionCollapse();
    loadLatestNotification();
});

// Initialize user selection collapse functionality
function initializeUserSelectionCollapse() {
    const collapseElement = document.getElementById('userSelectionCollapse');
    const toggleButton = document.querySelector('[data-bs-target="#userSelectionCollapse"]');
    
    if (collapseElement && toggleButton) {
        collapseElement.addEventListener('show.bs.collapse', function () {
            toggleButton.innerHTML = '<i class="bx bx-chevron-up me-1"></i> Hide Users';
            toggleButton.setAttribute('aria-expanded', 'true');
        });
        
        collapseElement.addEventListener('hide.bs.collapse', function () {
            toggleButton.innerHTML = '<i class="bx bx-chevron-down me-1"></i> Show Users';
            toggleButton.setAttribute('aria-expanded', 'false');
        });
    }
}

// Real-time update system
function initializeRealTimeUpdates() {
    // Check for updates every 30 seconds
    updateInterval = setInterval(checkForUpdates, 30000);
    
    // Show live indicator
    updateLiveIndicator();
}

function checkForUpdates() {
    $.ajax({
        url: '/user-stats/live-updates',
        method: 'GET',
        data: { 
            last_update: lastUpdateTime,
            days: {{ $days }},
            user_ids: getSelectedUserIds()
        },
        success: function(response) {
            if (response.has_updates) {
                handleLiveUpdates(response);
                lastUpdateTime = Date.now();
            }
        },
        error: function() {
            console.log('Failed to check for updates');
        }
    });
    
    // Also refresh notifications periodically (every 5 minutes)
    if (Date.now() - lastUpdateTime > 300000) { // 5 minutes
        loadLatestNotification();
    }
}

function handleLiveUpdates(response) {
    // Update counters
    if (response.updates.summary) {
        updateSummaryCards(response.updates.summary);
    }
    
    // Update table data
    if (response.updates.users) {
        updateUserRows(response.updates.users);
    }
    
    // Show notification for new comments
    if (response.updates.new_comments && response.updates.new_comments.length > 0) {
        showNewCommentNotifications(response.updates.new_comments);
        // Refresh notification widget
        loadLatestNotification();
    }
    
    // Show browser notification for new callbacks
    if (response.updates.new_callbacks && response.updates.new_callbacks.length > 0) {
        showBrowserNotification('New Callbacks', `${response.updates.new_callbacks.length} new callback(s) added`);
        // Refresh notification widget
        loadLatestNotification();
    }
    
    // Show browser notification for new comments with status info
    if (response.updates.new_comments && response.updates.new_comments.length > 0) {
        response.updates.new_comments.forEach(comment => {
            showBrowserNotification(
                `New Comment - ${comment.sales_status}`, 
                `${comment.user} commented on ${comment.client}: "${comment.comment.substring(0, 50)}${comment.comment.length > 50 ? '...' : ''}"`
            );
        });
    }
    
    // Show activity feed update
    showActivityFeedUpdate(response.updates);
}

function updateSummaryCards(summaryData) {
    // Update summary card values with smooth animation
    Object.keys(summaryData).forEach(key => {
        const element = document.querySelector(`[data-summary="${key}"]`);
        if (element) {
            animateValue(element, parseInt(element.textContent), summaryData[key], 1000);
        }
    });
}

function updateUserRows(userData) {
    userData.forEach(user => {
        const row = document.querySelector(`tr[data-user-id="${user.id}"]`);
        if (row) {
            // Update comment count
            const commentCell = row.querySelector('.comments-today');
            if (commentCell) {
                animateValue(commentCell, parseInt(commentCell.textContent), user.comments_today, 500);
            }
            
            // Update target progress
            const progressBar = row.querySelector('.target-fill');
            const progressText = row.querySelector('.target-status');
            if (progressBar && progressText) {
                const newProgress = Math.min(100, user.target_progress);
                progressBar.style.width = newProgress + '%';
                progressText.textContent = newProgress + '% Complete';
                progressText.className = `target-status ${newProgress >= 100 ? 'target-achieved' : 'target-pending'}`;
            }
        }
    });
}

function showNewCommentNotifications(comments) {
    comments.forEach(comment => {
        // Ensure sales_status has a default value if undefined
        const salesStatus = comment.sales_status || 'New';
        const statusBadgeClass = getClientStatusBadgeClass(salesStatus);
        const statusIcon = salesStatus === 'No Answer' ? 'bx-phone-off' : 
                          salesStatus === 'Call Back' ? 'bx-phone-call' : 'bx-user';
        
        const notificationContent = `
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <span class="badge bg-primary" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bx bx-message-dots me-1"></i>Comment
                    </span>
                </div>
                <div class="flex-grow-1">
                    <div class="mb-2">
                        <strong>${comment.user || 'Unknown'}</strong> commented on client <strong>${comment.client || 'Unknown'}</strong>
                        <span class="badge ${statusBadgeClass} ms-2" style="font-size: 0.75rem;">
                            <i class="bx ${statusIcon} me-1"></i>${salesStatus}
                        </span>
                    </div>
                    <div class="mt-1 p-2" style="background: #f8f9fa; border-radius: 4px; border-left: 3px solid #007bff; font-size: 0.9rem;">
                        "${comment.comment || 'No comment text'}"
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="bx bx-time me-1"></i>${comment.created_at || 'Unknown time'}
                        <span class="ms-3">
                            <i class="bx bx-user-circle me-1"></i>Client Status: <strong>${salesStatus}</strong>
                        </span>
                    </small>
                </div>
            </div>
        `;
        
        showNotificationToast('New Comment', notificationContent, true);
    });
}

function showNotificationToast(title, message, isHtml = false) {
    // Create notification toast
    const notification = document.createElement('div');
    notification.className = 'notification-badge alert alert-info alert-dismissible fade show';
    notification.style.maxWidth = '400px';
    notification.style.minWidth = '350px';
    
    if (isHtml) {
        notification.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <strong>${title}:</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            ${message}
        `;
    } else {
        notification.innerHTML = `
            <strong>${title}:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 8 seconds (longer for rich content)
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, isHtml ? 8000 : 5000);
}

function showBrowserNotification(title, message) {
    if (Notification.permission === 'granted') {
        new Notification(title, {
            body: message,
            icon: '/favicon.ico'
        });
    }
}

function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

function animateValue(element, start, end, duration) {
    const range = end - start;
    const minTimer = 50;
    let stepTime = Math.abs(Math.floor(duration / range));
    stepTime = Math.max(stepTime, minTimer);
    
    const startTime = Date.now();
    const endTime = startTime + duration;
    
    function run() {
        const now = Date.now();
        const remaining = Math.max((endTime - now) / duration, 0);
        const value = Math.round(end - (remaining * range));
        element.textContent = value;
        
        if (value !== end) {
            requestAnimationFrame(run);
        }
    }
    
    run();
}

function showActivityFeedUpdate(updates) {
    const activityText = [];
    
    if (updates.new_comments && updates.new_comments.length > 0) {
        activityText.push(`${updates.new_comments.length} new comment(s)`);
    }
    
    if (updates.new_callbacks && updates.new_callbacks.length > 0) {
        activityText.push(`${updates.new_callbacks.length} new callback(s)`);
    }
    
    if (activityText.length > 0) {
        const liveIndicator = document.querySelector('.live-indicator');
        if (liveIndicator) {
            liveIndicator.style.animation = 'pulse 0.5s ease-in-out 3';
        }
    }
}

function getSelectedUserIds() {
    const hiddenInputs = document.querySelectorAll('#hiddenInputs input[name="user_ids[]"]');
    return Array.from(hiddenInputs).map(input => input.value);
}

function updateLiveIndicator() {
    const indicator = document.querySelector('.live-indicator');
    if (indicator) {
        setInterval(() => {
            indicator.style.animation = 'none';
            setTimeout(() => {
                indicator.style.animation = 'pulse 2s infinite';
            }, 10);
        }, 10000);
    }
}

// Handle checkbox-based user selection
function toggleAllUsers() {
    const allUsersCheckbox = document.getElementById('all_users');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    const hiddenInputs = document.getElementById('hiddenInputs');
    
    if (allUsersCheckbox.checked) {
        // Uncheck all individual users
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Clear selected users display
        selectedDisplay.innerHTML = '<div class="text-center py-4"><i class="bx bx-group text-muted" style="font-size: 2rem;"></i><p class="text-muted mb-0">All Users Selected</p></div>';
        
        // Clear hidden inputs
        hiddenInputs.innerHTML = '';
    }
}

function handleUserSelection(userId, username, fullName) {
    const userCheckbox = document.getElementById(`user_${userId}`);
    const allUsersCheckbox = document.getElementById('all_users');
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    const hiddenInputs = document.getElementById('hiddenInputs');
    
    // Uncheck "All Users" when any individual user is selected
    allUsersCheckbox.checked = false;
    
    if (userCheckbox.checked) {
        // Add user to selected display
        addUserToDisplay(userId, username);
        
        // Add hidden input
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'user_ids[]';
        hiddenInput.value = userId;
        hiddenInput.id = `hidden_${userId}`;
        hiddenInputs.appendChild(hiddenInput);
    } else {
        // Remove user from display and hidden inputs
        removeUserFromDisplay(userId);
    }
    
    updateSelectedDisplay();
}

function addUserToDisplay(userId, username) {
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    
    // Remove "All Users Selected" text if present
    const allUsersText = selectedDisplay.querySelector('.text-center');
    if (allUsersText) {
        allUsersText.remove();
    }
    
    // Create user badge
    const userBadge = document.createElement('div');
    userBadge.className = 'badge bg-primary text-white me-1 mb-1 p-2';
    userBadge.id = `selected_${userId}`;
    userBadge.innerHTML = `
        ${username}
        <button type="button" class="btn-close btn-close-white ms-1" 
                style="font-size: 0.7rem;" onclick="removeUser(${userId})"></button>
    `;
    
    selectedDisplay.appendChild(userBadge);
    
    // Update display panel height based on content
    updateSelectedUsersHeight();
}

function updateSelectedUsersHeight() {
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    const badges = selectedDisplay.querySelectorAll('.badge');
    
    if (badges.length === 0) {
        selectedDisplay.style.minHeight = '80px';
    } else {
        // Calculate height based on number of badges
        const rows = Math.ceil(badges.length / 2); // Assuming 2 badges per row
        const minHeight = Math.max(80, (rows * 40) + 20); // 40px per row + padding
        selectedDisplay.style.minHeight = minHeight + 'px';
    }
}

function removeUser(userId) {
    // Uncheck the checkbox
    const userCheckbox = document.getElementById(`user_${userId}`);
    if (userCheckbox) {
        userCheckbox.checked = false;
    }
    
    // Remove from display
    removeUserFromDisplay(userId);
    
    updateSelectedDisplay();
}

function removeUserFromDisplay(userId) {
    // Remove from selected display
    const selectedBadge = document.getElementById(`selected_${userId}`);
    if (selectedBadge) {
        selectedBadge.remove();
    }
    
    // Remove hidden input
    const hiddenInput = document.getElementById(`hidden_${userId}`);
    if (hiddenInput) {
        hiddenInput.remove();
    }
    
    // Update height after removal
    updateSelectedUsersHeight();
}

function updateSelectedDisplay() {
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    const selectedBadges = selectedDisplay.querySelectorAll('.badge');
    
    // If no users are selected, show "All Users Selected"
    if (selectedBadges.length === 0) {
        selectedDisplay.innerHTML = '<div class="text-center py-3"><i class="bx bx-group text-muted" style="font-size: 1.5rem;"></i><p class="text-muted mb-0 small">All Users Selected</p></div>';
        document.getElementById('all_users').checked = true;
        selectedDisplay.style.minHeight = '80px';
    } else {
        updateSelectedUsersHeight();
    }
}

function selectAllUsers() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const allUsersCheckbox = document.getElementById('all_users');
    
    // Uncheck "All Users"
    allUsersCheckbox.checked = false;
    
    // Check all individual users
    userCheckboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            checkbox.checked = true;
            const userId = checkbox.value;
            const label = document.querySelector(`label[for="user_${userId}"]`);
            const username = label.textContent.trim().split(' (')[0];
            
            // Add to display and hidden inputs
            addUserToDisplay(userId, username);
            
            const hiddenInputs = document.getElementById('hiddenInputs');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'user_ids[]';
            hiddenInput.value = userId;
            hiddenInput.id = `hidden_${userId}`;
            hiddenInputs.appendChild(hiddenInput);
        }
    });
}

function clearAllUsers() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const allUsersCheckbox = document.getElementById('all_users');
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    const hiddenInputs = document.getElementById('hiddenInputs');
    
    // Uncheck all individual users
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check "All Users"
    allUsersCheckbox.checked = true;
    
    // Clear displays and inputs
    selectedDisplay.innerHTML = '<div class="text-center py-3"><i class="bx bx-group text-muted" style="font-size: 1.5rem;"></i><p class="text-muted mb-0 small">All Users Selected</p></div>';
    selectedDisplay.style.minHeight = '80px';
    hiddenInputs.innerHTML = '';
}

function resetFilters() {
    // Reset to default values
    document.getElementById('days').value = '1';
    document.getElementById('comments_min').value = '';
    document.getElementById('comments_max').value = '';
    clearAllUsers();
}

function setCommentsFilter(min, max) {
    document.getElementById('comments_min').value = min !== null ? min : '';
    document.getElementById('comments_max').value = max !== null ? max : '';
    
    // Submit the form automatically
    document.querySelector('form').submit();
}

// Show user report for past periods
function showUserReport(userId, username, period, dateFrom, dateTo, reportType = 'all') {
    const modal = new bootstrap.Modal(document.getElementById('clientDetailsModal'));
    
    // Set modal title
    document.getElementById('clientDetailsModalLabel').innerHTML = `
        <i class="bx bx-file-blank me-2"></i>User Report - ${username}
        <small class="text-muted ms-2">(${dateFrom} to ${dateTo})</small>
    `;
    
    // Show loading
    const contentDiv = document.getElementById('clientDetailsContent');
    contentDiv.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="text-muted">Loading User Report...</h5>
            <p class="text-muted">Generating detailed activity report for ${username}</p>
        </div>
    `;
    
    // Hide other modal sections
    document.getElementById('modalCommentsFilter').style.display = 'none';
    document.getElementById('statusChangeFilter').style.display = 'none';
    document.getElementById('clientTransferSection').style.display = 'none';
    
    modal.show();
    
    // Fetch report data
    $.ajax({
        url: '{{ route("user.stats.report") }}',
        method: 'GET',
        data: {
            user_id: userId,
            date_from: dateFrom,
            date_to: dateTo,
            period: period,
            report_type: reportType
        },
        success: function(response) {
            contentDiv.innerHTML = generateUserReportHTML(response, username, period, dateFrom, dateTo, reportType);
        },
        error: function(xhr) {
            console.error('Error loading user report:', xhr);
            contentDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bx bx-error me-2"></i>
                    <strong>Error Loading Report</strong><br>
                    Unable to load the user report. Please try again.
                </div>
            `;
        }
    });
}

function generateUserReportHTML(data, username, period, dateFrom, dateTo, reportType) {
    const periodText = period === 'yesterday' ? 'Yesterday' : 
                      period === '3' ? 'Last 3 Days' :
                      period === '7' ? 'Last 7 Days' :
                      period === '30' ? 'Last 30 Days' : 
                      `${dateFrom} to ${dateTo}`;
    
    let html = `
        <div class="user-report-container">
            <!-- Report Header -->
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-white mb-1">
                                <i class="bx bx-user me-2"></i>${username} - Activity Report
                            </h4>
                            <p class="mb-0 text-white-50">
                                <i class="bx bx-calendar me-1"></i>Period: ${periodText}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-light btn-sm" onclick="exportUserReport('${username}', '${periodText}')">
                                <i class="bx bx-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h3 class="text-success mb-1">${data.summary.new_clients}</h3>
                            <small class="text-muted">New Clients</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h3 class="text-warning mb-1">${data.summary.callbacks}</h3>
                            <small class="text-muted">Callbacks</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h3 class="text-primary mb-1">${data.summary.no_answers}</h3>
                            <small class="text-muted">No Answer</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h3 class="text-info mb-1">${data.summary.comments}</h3>
                            <small class="text-muted">Comments</small>
                        </div>
                    </div>
                </div>
            </div>
    `;
    
    // Filter tabs for different report sections
    if (reportType === 'all') {
        html += `
            <!-- Report Sections -->
            <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-clients" type="button" role="tab">
                        <i class="bx bx-list-ul me-1"></i>All Activity (${data.all_clients ? data.all_clients.length : 0})
                    </button>
                </li>
                ${data.new_clients && data.new_clients.length > 0 ? `
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-clients" type="button" role="tab">
                        <i class="bx bx-user-plus me-1"></i>New Clients (${data.new_clients.length})
                    </button>
                </li>` : ''}
                ${data.callbacks && data.callbacks.length > 0 ? `
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="callbacks-tab" data-bs-toggle="tab" data-bs-target="#callbacks-clients" type="button" role="tab">
                        <i class="bx bx-phone-call me-1"></i>Callbacks (${data.callbacks.length})
                    </button>
                </li>` : ''}
                ${data.no_answers && data.no_answers.length > 0 ? `
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="no-answers-tab" data-bs-toggle="tab" data-bs-target="#no-answers-clients" type="button" role="tab">
                        <i class="bx bx-phone-off me-1"></i>No Answer (${data.no_answers.length})
                    </button>
                </li>` : ''}
            </ul>
            
            <div class="tab-content" id="reportTabsContent">
        `;
    }
    
    // Generate client tables based on report type
    if (reportType === 'all' || reportType === 'new_clients') {
        html += generateClientTable(data.all_clients || data.new_clients, 'All Activity', reportType === 'all' ? 'all-clients' : 'new-clients', reportType === 'all' ? 'active' : '');
    }
    
    if ((reportType === 'all' || reportType === 'new_clients') && data.new_clients && data.new_clients.length > 0 && reportType === 'all') {
        html += generateClientTable(data.new_clients, 'New Clients', 'new-clients', '');
    }
    
    if ((reportType === 'all' || reportType === 'callbacks') && data.callbacks && data.callbacks.length > 0) {
        html += generateClientTable(data.callbacks, 'Callbacks', 'callbacks-clients', reportType === 'callbacks' ? 'active' : '');
    }
    
    if ((reportType === 'all' || reportType === 'no_answer') && data.no_answers && data.no_answers.length > 0) {
        html += generateClientTable(data.no_answers, 'No Answer', 'no-answers-clients', reportType === 'no_answer' ? 'active' : '');
    }
    
    if (reportType === 'all') {
        html += '</div>'; // Close tab-content
    }
    
    html += '</div>'; // Close user-report-container
    
    return html;
}

function generateClientTable(clients, title, tabId, activeClass) {
    if (!clients || clients.length === 0) {
        return `
            <div class="tab-pane fade ${activeClass}" id="${tabId}" role="tabpanel">
                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>No ${title.toLowerCase()} found for this period.
                </div>
            </div>
        `;
    }
    
    return `
        <div class="tab-pane fade ${activeClass} show" id="${tabId}" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Last Comment</th>
                            <th>Comments Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${clients.map(client => `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            ${(client.first_name?.charAt(0) || '?')}${(client.last_name?.charAt(0) || '')}
                                        </div>
                                        <div>
                                            <strong>${client.first_name || 'N/A'} ${client.last_name || ''}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>${client.email || 'N/A'}</td>
                                <td>${client.phone1 || 'N/A'}</td>
                                <td>
                                    <span class="badge ${getStatusBadgeClass(client.sales_status)}">
                                        ${client.sales_status || 'New'}
                                    </span>
                                </td>
                                <td>${client.created_at ? new Date(client.created_at).toLocaleDateString() : 'N/A'}</td>
                                <td>
                                    ${client.last_comment ? `
                                        <div class="comment-box" style="max-width: 200px;">
                                            <small class="text-muted">${client.last_comment.substring(0, 100)}${client.last_comment.length > 100 ? '...' : ''}</small>
                                        </div>
                                    ` : '<small class="text-muted">No comments</small>'}
                                </td>
                                <td>
                                    <span class="badge bg-info">${client.comments_count || 0}</span>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function getStatusBadgeClass(status) {
    switch(status?.toLowerCase()) {
        case 'call back': return 'bg-warning';
        case 'no answer': return 'bg-primary';
        case 'new': return 'bg-success';
        case 'not interested': return 'bg-danger';
        case 'interested': return 'bg-info';
        default: return 'bg-secondary';
    }
}

function exportUserReport(username, period) {
    // Simple export functionality - could be enhanced to generate PDF/Excel
    const reportContent = document.getElementById('clientDetailsContent').innerText;
    const blob = new Blob([reportContent], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${username}_Report_${period.replace(/\s+/g, '_')}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}



function showClientDetails(userId, status, days, username) {
    // Store global variables for filtering
    window.currentModalData = {
        userId: userId,
        status: status,
        days: days,
        username: username,
        originalResponse: null
    };
    
    $('#clientDetailsModal').modal('show');
    $('#clientDetailsModalLabel').text(`${username} - ${status} Clients - Details with Last 3 Comments`);
    
    // Show the appropriate filter based on status
    if (status === 'Call Back' || status === 'No Answer') {
        $('#modalCommentsFilter').show();
        $('#statusChangeFilter').hide();
        clearModalCommentsFilter();
    } else if (status === 'Status Changed') {
        $('#modalCommentsFilter').hide();
        $('#statusChangeFilter').show();
        clearStatusChangeFilter();
    } else {
        $('#modalCommentsFilter').hide();
        $('#statusChangeFilter').hide();
    }
    
    // Reset transfer section
    $('#clientTransferSection').hide();
    $('#transferToUser').val('');
    $('#selectedClientCount').text('0');
    
    // Show loading
    $('#clientDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    // Fetch client details
    $.ajax({
        url: `/user-stats/client-details/${userId}/${status}`,
        method: 'GET',
        data: { days: days, include_target_data: true },
        success: function(response) {
            window.currentModalData.originalResponse = response;
            renderClientDetailsTable(response);
        },
        error: function() {
            $('#clientDetailsContent').html(`
                <div class="alert alert-danger" role="alert">
                    Failed to load client details. Please try again.
                </div>
            `);
        }
    });
}

function showStatusChangedClients(userId, username) {
    // Store global variables for filtering
    window.currentModalData = {
        userId: userId,
        status: 'Status Changed',
        days: 1, // Always today for status changes
        username: username,
        originalResponse: null
    };
    
    $('#clientDetailsModal').modal('show');
    $('#clientDetailsModalLabel').text(`${username} - Today's New Clients Status Changes`);
    
    // Show status change filter instead of comments filter
    $('#modalCommentsFilter').hide();
    $('#statusChangeFilter').show();
    clearStatusChangeFilter();
    
    // Reset transfer section
    $('#clientTransferSection').hide();
    $('#transferToUser').val('');
    $('#selectedClientCount').text('0');
    
    // Show loading
    $('#clientDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    // Fetch status changed clients (always for today only)
    $.ajax({
        url: `/user-stats/status-changed-clients/${userId}`,
        method: 'GET',
        success: function(response) {
            window.currentModalData.originalResponse = response;
            renderStatusChangedClientsTable(response);
        },
        error: function() {
            $('#clientDetailsContent').html(`
                <div class="alert alert-danger" role="alert">
                    Failed to load status changed clients. Please try again.
                </div>
            `);
        }
    });
}

// Cleanup when page is unloaded
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});

// Modal filtering functions
function setModalCommentsFilter(min, max) {
    document.getElementById('modal_comments_min').value = min !== null ? min : '';
    document.getElementById('modal_comments_max').value = max !== null ? max : '';
}

function applyModalCommentsFilter() {
    if (!window.currentModalData || !window.currentModalData.originalResponse) {
        return;
    }
    
    const min = document.getElementById('modal_comments_min').value;
    const max = document.getElementById('modal_comments_max').value;
    
    let filteredClients = window.currentModalData.originalResponse.clients;
    
    // Apply filter if values are set
    if (min !== '' || max !== '') {
        filteredClients = window.currentModalData.originalResponse.clients.filter(item => {
            const commentCount = item.comments_count_period || 0;
            
            if (min !== '' && commentCount < parseInt(min)) {
                return false;
            }
            if (max !== '' && commentCount > parseInt(max)) {
                return false;
            }
            return true;
        });
        
        // Update filter status
        let statusText = 'Filtered: ';
        if (min !== '' && max !== '') {
            statusText += `${min}-${max} comments`;
        } else if (min !== '') {
            statusText += `≥${min} comments`;
        } else {
            statusText += `≤${max} comments`;
        }
        statusText += ` (${filteredClients.length} clients)`;
        document.getElementById('modalFilterStatus').textContent = statusText;
    } else {
        document.getElementById('modalFilterStatus').textContent = '';
    }
    
    // Create filtered response object
    const filteredResponse = {
        ...window.currentModalData.originalResponse,
        clients: filteredClients
    };
    
    renderClientDetailsTable(filteredResponse);
}

function clearModalCommentsFilter() {
    document.getElementById('modal_comments_min').value = '';
    document.getElementById('modal_comments_max').value = '';
    document.getElementById('modalFilterStatus').textContent = '';
    
    if (window.currentModalData && window.currentModalData.originalResponse) {
        renderClientDetailsTable(window.currentModalData.originalResponse);
    }
}

// Status Change Filter Functions
function applyStatusChangeFilter() {
    if (!window.currentModalData || !window.currentModalData.originalResponse) {
        return;
    }
    
    const filterType = document.getElementById('statusChangeType').value;
    let filteredClients = window.currentModalData.originalResponse.clients;
    
    // Apply filter based on selection
    if (filterType !== 'all') {
        filteredClients = window.currentModalData.originalResponse.clients.filter(client => {
            if (filterType === 'callback') {
                return client.sales_status === 'Call Back';
            } else if (filterType === 'no_answer') {
                return client.sales_status === 'No Answer';
            }
            return true;
        });
        
        // Update filter status
        let statusText = '';
        let badgeClass = '';
        if (filterType === 'callback') {
            statusText = `Filtered: Call Back Only (${filteredClients.length} clients)`;
            badgeClass = 'text-primary';
        } else if (filterType === 'no_answer') {
            statusText = `Filtered: No Answer Only (${filteredClients.length} clients)`;
            badgeClass = 'text-warning';
        }
        document.getElementById('statusChangeFilterStatus').textContent = statusText;
        document.getElementById('statusChangeFilterStatus').className = `small ${badgeClass} fw-bold`;
    } else {
        document.getElementById('statusChangeFilterStatus').textContent = '';
    }
    
    // Create filtered response object
    const filteredResponse = {
        ...window.currentModalData.originalResponse,
        clients: filteredClients,
        // Update counts based on filtered results
        callback_count: filteredClients.filter(c => c.sales_status === 'Call Back').length,
        no_answer_count: filteredClients.filter(c => c.sales_status === 'No Answer').length,
        total_changed: filteredClients.length
    };
    
    renderStatusChangedClientsTable(filteredResponse);
}

function clearStatusChangeFilter() {
    document.getElementById('statusChangeType').value = 'all';
    document.getElementById('statusChangeFilterStatus').textContent = '';
    
    if (window.currentModalData && window.currentModalData.originalResponse) {
        renderStatusChangedClientsTable(window.currentModalData.originalResponse);
    }
}

function renderStatusChangedClientsTable(response) {
    let html = `
        <div class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h6>Today's New Clients Status Changes: <span class="badge bg-info">${response.total_changed}</span></h6>
                    <p class="text-muted mb-0">Period: ${response.period || 'Today'} - New clients that changed status</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>
                        <span class="badge bg-primary me-1">${response.callback_count} to Callback</span>
                        <span class="badge bg-warning">${response.no_answer_count} to No Answer</span>
                    </h6>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-dark">Today's New Clients with Status Changes:</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAllClients" onchange="toggleAllClientSelection()">
                                    <label for="selectAllClients" class="ms-1 small">All</label>
                                </th>
                                <th>Client</th>
                                <th>Contact Info</th>
                                <th class="text-center">Current Status</th>
                                <th class="text-center">Status Change Date</th>
                                <th>Timeline</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    if (response.clients.length > 0) {
        response.clients.forEach(function(client) {
            const statusBadgeClass = client.sales_status === 'No Answer' ? 'bg-warning' : 'bg-primary';
            const statusIcon = client.sales_status === 'No Answer' ? 'bx-phone-off' : 'bx-phone-call';
            
            html += `
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="client-checkbox" 
                               data-client-id="${client.id}" 
                               data-client-name="${client.first_name} ${client.last_name || ''}" 
                               onchange="handleClientCheckboxChange()">
                    </td>
                    <td>
                        <div>
                            <h6 class="mb-0">${client.first_name} ${client.last_name || ''}</h6>
                            <small class="text-muted">ID: ${client.id}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <small class="text-muted d-block"><i class="bx bx-phone me-1"></i>${client.phone1 || 'N/A'}</small>
                            <small class="text-muted"><i class="bx bx-envelope me-1"></i>${client.email || 'N/A'}</small>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge ${statusBadgeClass}">
                            <i class="bx ${statusIcon} me-1"></i>${client.sales_status}
                        </span>
                    </td>
                    <td class="text-center">
                        <div>
                            <small class="fw-bold">${new Date(client.updated_at).toLocaleDateString()}</small>
                            <br>
                            <small class="text-muted">${new Date(client.updated_at).toLocaleTimeString()}</small>
                        </div>
                    </td>
                    <td>
                        <div class="timeline-item">
                            <small class="text-muted d-block">
                                <strong>Created:</strong> ${new Date(client.created_at).toLocaleDateString()} 
                                <span class="badge bg-light text-dark">New</span>
                            </small>
                            <i class="bx bx-down-arrow-alt text-muted"></i>
                            <small class="text-success d-block">
                                <strong>Changed:</strong> ${new Date(client.updated_at).toLocaleDateString()} 
                                <span class="badge ${statusBadgeClass}">${client.sales_status}</span>
                            </small>
                        </div>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="text-muted">
                        <i class="bx bx-info-circle mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">No new clients with status changes found for today.</p>
                    </div>
                </td>
            </tr>
        `;
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    $('#clientDetailsContent').html(html);
}

function renderClientDetailsTable(response) {
    let html = `
        <div class="mb-4">
            <div class="row">
                <div class="col-md-8">
                    <h6>Status: <span class="badge bg-primary">${response.status}</span></h6>
                    <p class="text-muted mb-0">Client details with last 3 comments for each client</p>
                </div>
                <div class="col-md-4 text-end">
                    <h6>Total Clients: <span class="badge bg-info">${response.clients.length}</span></h6>
                    <h6>Total Comments: <span class="badge bg-success">${response.last_comments.length}</span></h6>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-dark">Client Summary with Last 3 Comments:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAllClients" onchange="toggleAllClientSelection()">
                                    <label for="selectAllClients" class="ms-1 small">All</label>
                                </th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Comments (Period)</th>
                                <th>Target Status</th>
                                <th>Comments History</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    if (response.clients.length > 0) {
        response.clients.forEach(function(item, index) {
            const client = item.client;
            const commentsToday = item.comments_count_period || 0;
            const targetMet = commentsToday >= 3;
            const remaining = Math.max(0, 3 - commentsToday);
            
            html += `
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="client-checkbox" 
                               data-client-id="${client.id}" 
                               data-client-name="${client.first_name} ${client.last_name || ''}" 
                               onchange="handleClientCheckboxChange()">
                    </td>
                    <td>
                        <strong>${client.first_name} ${client.last_name || ''}</strong>
                        <br><small class="text-muted">ID: ${client.id}</small>
                    </td>
                    <td>
                        <small class="text-muted d-block"><i class="bx bx-phone me-1"></i>${client.phone1 || 'N/A'}</small>
                        <small class="text-muted"><i class="bx bx-envelope me-1"></i>${client.email || 'N/A'}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge ${targetMet ? 'bg-success' : 'bg-warning'}">${commentsToday}</span>
                    </td>
                    <td class="text-center">
                        ${targetMet ? 
                            '<span class="badge bg-success">✓ Target Met</span>' : 
                            `<span class="badge bg-warning">${remaining} needed</span>`
                        }
                    </td>
                    <td style="max-width: 400px; min-width: 300px;">
            `;
            
            // Show comments
            if (commentsToday === 0) {
                html += `
                    <div class="text-center py-3">
                        <small class="text-muted">
                            <i class="bx bx-message-x"></i> No comments in period
                        </small>
                    </div>
                `;
            } else {
                let clientComments = [];
                if (response.last_comments && response.last_comments.length > 0) {
                    clientComments = response.last_comments.filter(comment => 
                        comment.client && comment.client.id === client.id
                    );
                }
                
                if (clientComments.length > 0) {
                    html += '<div class="d-flex flex-column gap-2">';
                    clientComments.slice(0, 3).forEach(function(comment) {
                        html += `
                            <div class="border rounded p-2 comment-box" style="background: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <small class="fw-bold text-primary">${comment.user ? comment.user.username : 'Unknown'}</small>
                                    <small class="text-muted">${comment.formatted_datetime}</small>
                                </div>
                                <div class="small text-dark comment-text comment-container">${comment.comment}</div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += `
                        <div class="text-center py-3">
                            <small class="text-muted">
                                <i class="bx bx-message-x"></i> No comments yet
                            </small>
                        </div>
                    `;
                }
            }
            
            html += `
                    </td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="text-muted">
                        <i class="bx bx-info-circle mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">No clients match the current filter criteria.</p>
                    </div>
                </td>
            </tr>
        `;
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    $('#clientDetailsContent').html(html);
}

// Notification Functions
function loadLatestNotification() {
    $.ajax({
        url: '/user-stats/latest-notification',
        method: 'GET',
        success: function(response) {
            updateLatestNotificationDisplay(response);
        },
        error: function() {
            document.getElementById('latestNotificationContent').innerHTML = 
                '<small class="text-white-50">No notifications</small>';
            document.getElementById('latestNotificationTime').textContent = '';
            document.getElementById('notificationCount').textContent = '0';
        }
    });
}

function updateLatestNotificationDisplay(data) {
    const contentElement = document.getElementById('latestNotificationContent');
    const timeElement = document.getElementById('latestNotificationTime');
    const countElement = document.getElementById('notificationCount');
    
    if (data.latest_notification) {
        const notification = data.latest_notification;
        const preview = truncateText(notification.message, 60); // Shortened to make room for status
        const statusBadge = getStatusBadge(notification.client_status);
        
        contentElement.innerHTML = `
            <div class="latest-notification-preview">
                <div class="d-flex align-items-center mb-1">
                    <strong class="me-2">${notification.type}:</strong>
                    ${statusBadge}
                </div>
                <span class="small">${preview}</span>
            </div>
        `;
        
        timeElement.textContent = notification.formatted_time || 'Just now';
        countElement.textContent = data.total_count || '0';
    } else {
        contentElement.innerHTML = '<small class="text-white-50">No notifications</small>';
        timeElement.textContent = 'Click to view all';
        countElement.textContent = '0';
    }
}

function showNotificationsModal() {
    $('#notificationsModal').modal('show');
    loadAllNotifications();
}

function loadAllNotifications() {
    $('#notificationsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    $.ajax({
        url: '/user-stats/notifications',
        method: 'GET',
        data: { limit: 10 },
        success: function(response) {
            renderNotificationsList(response.notifications);
        },
        error: function() {
            $('#notificationsContent').html(`
                <div class="alert alert-danger" role="alert">
                    <i class="bx bx-error me-2"></i>Failed to load notifications. Please try again.
                </div>
            `);
        }
    });
}

function renderNotificationsList(notifications) {
    let html = '';
    
    if (notifications && notifications.length > 0) {
        notifications.forEach(function(notification, index) {
            const typeIcon = getNotificationIcon(notification.type);
            const typeBadgeClass = getNotificationBadgeClass(notification.type);
            const userInitials = notification.user_name ? notification.user_name.substring(0, 2).toUpperCase() : 'SY';
            
            html += `
                <div class="notification-card ${notification.is_read ? '' : 'unread'} p-3 mb-3">
                    <div class="d-flex align-items-start">
                        <div class="notification-avatar me-3" style="background: ${getRandomColor(index)};">
                            ${userInitials}
                        </div>
                        <div class="notification-content">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge ${typeBadgeClass} notification-type-badge me-2">
                                        <i class="bx ${typeIcon} me-1"></i>${notification.type}
                                    </span>
                                    ${!notification.is_read ? '<span class="badge bg-success notification-type-badge">New</span>' : ''}
                                    ${notification.client_status ? `<span class="badge ${getClientStatusBadgeClass(notification.client_status)} notification-type-badge ms-1">${notification.client_status}</span>` : ''}
                                </div>
                                <small class="notification-time">${notification.formatted_time}</small>
                            </div>
                            <div class="mb-2">
                                <strong>${notification.title || notification.type}</strong>
                            </div>
                            <div class="text-muted">
                                ${notification.message}
                            </div>
                            ${notification.client_name ? `
                                <div class="mt-2">
                                    <small class="text-primary">
                                        <i class="bx bx-user me-1"></i>Client: ${notification.client_name}
                                        ${notification.client_status ? `<span class="ms-2 badge ${getClientStatusBadgeClass(notification.client_status)} badge-sm">${notification.client_status}</span>` : ''}
                                    </small>
                                </div>
                            ` : ''}
                            ${notification.user_name ? `
                                <div class="mt-1">
                                    <small class="text-info">
                                        <i class="bx bx-user-circle me-1"></i>User: ${notification.user_name}
                                    </small>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html = `
            <div class="text-center py-5">
                <i class="bx bx-bell-off text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">No notifications found</h5>
                <p class="text-muted">New notifications will appear here when they arrive.</p>
            </div>
        `;
    }
    
    $('#notificationsContent').html(html);
}

function refreshNotifications() {
    loadAllNotifications();
    loadLatestNotification();
}

function getNotificationIcon(type) {
    const icons = {
        'Comment': 'bx-message-dots',
        'Status Change': 'bx-transfer',
        'New Client': 'bx-user-plus',
        'Client Transfer': 'bx-share-alt',
        'System': 'bx-cog',
        'Alert': 'bx-bell',
        'default': 'bx-info-circle'
    };
    return icons[type] || icons['default'];
}

function getNotificationBadgeClass(type) {
    const classes = {
        'Comment': 'bg-primary',
        'Status Change': 'bg-warning',
        'New Client': 'bg-success',
        'Client Transfer': 'bg-info',
        'System': 'bg-secondary',
        'Alert': 'bg-danger',
        'default': 'bg-light text-dark'
    };
    return classes[type] || classes['default'];
}

function getRandomColor(index) {
    const colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'];
    return colors[index % colors.length];
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

function getStatusBadge(status) {
    if (!status) return '';
    const badgeClass = getClientStatusBadgeClass(status);
    return `<span class="badge ${badgeClass}" style="font-size: 0.65rem; padding: 2px 6px;">${status}</span>`;
}

function getClientStatusBadgeClass(status) {
    const statusClasses = {
        'New': 'bg-success',
        'Call Back': 'bg-primary',
        'No Answer': 'bg-warning text-dark',
        'Closed': 'bg-secondary',
        'Interested': 'bg-info',
        'Not Interested': 'bg-danger',
        'default': 'bg-light text-dark'
    };
    return statusClasses[status] || statusClasses['default'];
}

// Client Selection and Transfer Functions
function toggleAllClientSelection() {
    const selectAllCheckbox = document.getElementById('selectAllClients');
    const clientCheckboxes = document.querySelectorAll('.client-checkbox');
    
    clientCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    handleClientCheckboxChange();
}

function handleClientCheckboxChange() {
    const selectedCheckboxes = document.querySelectorAll('.client-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    // Update selected count
    document.getElementById('selectedClientCount').textContent = selectedCount;
    
    // Show/hide transfer section based on selection
    const transferSection = document.getElementById('clientTransferSection');
    if (selectedCount > 0) {
        transferSection.style.display = 'block';
    } else {
        transferSection.style.display = 'none';
    }
    
    // Update "select all" checkbox state
    const selectAllCheckbox = document.getElementById('selectAllClients');
    const allCheckboxes = document.querySelectorAll('.client-checkbox');
    
    if (selectedCount === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (selectedCount === allCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
    }
}

function clearClientSelection() {
    // Uncheck all checkboxes
    document.querySelectorAll('.client-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    document.getElementById('selectAllClients').checked = false;
    document.getElementById('selectAllClients').indeterminate = false;
    
    // Hide transfer section
    document.getElementById('clientTransferSection').style.display = 'none';
    
    // Clear transfer user selection
    document.getElementById('transferToUser').value = '';
    
    // Update count
    document.getElementById('selectedClientCount').textContent = '0';
}

function transferSelectedClients() {
    const selectedCheckboxes = document.querySelectorAll('.client-checkbox:checked');
    const transferToUserId = document.getElementById('transferToUser').value;
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one client to transfer.');
        return;
    }
    
    if (!transferToUserId) {
        alert('Please select a user to transfer clients to.');
        return;
    }
    
    // Get selected client IDs and names
    const selectedClients = Array.from(selectedCheckboxes).map(checkbox => ({
        id: checkbox.getAttribute('data-client-id'),
        name: checkbox.getAttribute('data-client-name')
    }));
    
    // Get target user name
    const transferToUserSelect = document.getElementById('transferToUser');
    const targetUserName = transferToUserSelect.options[transferToUserSelect.selectedIndex].text;
    
    // Confirm transfer
    const clientNames = selectedClients.map(client => client.name).join(', ');
    const confirmMessage = `Are you sure you want to transfer ${selectedClients.length} client(s) to ${targetUserName}?\n\nClients: ${clientNames}`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Show loading state
    const transferBtn = document.querySelector('[onclick="transferSelectedClients()"]');
    const originalText = transferBtn.innerHTML;
    transferBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Transferring...';
    transferBtn.disabled = true;
    
    // Perform transfer via AJAX
    $.ajax({
        url: '/user-stats/transfer-clients',
        method: 'POST',
        data: {
            client_ids: selectedClients.map(client => client.id),
            target_user_id: transferToUserId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Show success notification
                showNotificationToast('Transfer Successful', 
                    `Successfully transferred ${response.transferred_count} client(s) to ${targetUserName}`, false);
                
                // Clear selection
                clearClientSelection();
                
                // Refresh the modal data
                if (window.currentModalData) {
                    showClientDetails(
                        window.currentModalData.userId,
                        window.currentModalData.status,
                        window.currentModalData.days,
                        window.currentModalData.username
                    );
                }
                
                // Optionally refresh the main page data
                setTimeout(() => {
                    location.reload();
                }, 2000);
                
            } else {
                alert('Transfer failed: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr) {
            let errorMessage = 'Transfer failed. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            alert(errorMessage);
        },
        complete: function() {
            // Restore button state
            transferBtn.innerHTML = originalText;
            transferBtn.disabled = false;
        }
    });
}
</script>
@endsection

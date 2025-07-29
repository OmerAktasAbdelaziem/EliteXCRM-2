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
                                                <h5 class="mb-1 fw-bold text-dark">{{ $noAnswerClients }}</h5>
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


@endsection

@section('script')
<script>
// Real-time Updates System
let updateInterval;
let lastUpdateTime = Date.now();

// Initialize real-time features
document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeUpdates();
    requestNotificationPermission();
    initializeUserSelectionCollapse();
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
    }
    
    // Show browser notification for new callbacks
    if (response.updates.new_callbacks && response.updates.new_callbacks.length > 0) {
        showBrowserNotification('New Callbacks', `${response.updates.new_callbacks.length} new callback(s) added`);
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
        const statusBadgeClass = salesStatus === 'No Answer' ? 'bg-warning' : 
                                salesStatus === 'Call Back' ? 'bg-primary' : 'bg-secondary';
        const statusIcon = salesStatus === 'No Answer' ? 'bx-phone-off' : 
                          salesStatus === 'Call Back' ? 'bx-phone-call' : 'bx-user';
        
        const notificationContent = `
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <span class="badge ${statusBadgeClass}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bx ${statusIcon} me-1"></i>${salesStatus}
                    </span>
                </div>
                <div class="flex-grow-1">
                    <div class="mb-2">
                        <strong>${comment.user || 'Unknown'}</strong> commented on client <strong>${comment.client || 'Unknown'}</strong>
                        <span class="badge bg-light text-dark ms-2" style="font-size: 0.75rem;">
                            Status: ${salesStatus}
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





function startLiveScreenCapture(userId, username) {
    const html = `
        <div class="live-monitoring-container">
            <!-- Live Screen Preview -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bx bx-video me-2"></i>Live Screen - ${username}
                                <span class="badge bg-light text-success ms-2">
                                    <i class="bx bx-circle blink-animation"></i> LIVE
                                </span>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-light" onclick="toggleFullscreen()">
                                    <i class="bx bx-fullscreen"></i> Fullscreen
                                </button>
                                <button class="btn btn-sm btn-light ms-1" onclick="takeScreenshot()">
                                    <i class="bx bx-camera"></i> Screenshot
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="live-screen-container" style="position: relative; background: #000; min-height: 400px;">
                                <!-- Simulated Live Screen -->
                                <div id="liveScreen" class="live-screen-display">
                                    <iframe src="about:blank" id="userScreenFrame" width="100%" height="400" 
                                            style="border: none; background: #f8f9fa;"></iframe>
                                    
                                    <!-- Live Overlay Information -->
                                    <div class="live-overlay">
                                        <div class="overlay-info">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-primary">Current Page: <span id="currentPage">Dashboard</span></span>
                                                <span class="badge bg-warning">Mouse Position: <span id="mousePos">X: 234, Y: 567</span></span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="badge bg-info">Last Click: <span id="lastClick">Client Management Button</span></span>
                                                <span class="badge bg-success">Status: <span id="userStatus">Active</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Real-time Activity Feed -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="bx bx-activity me-2"></i>Real-time Activity Stream
                            <span class="badge bg-success ms-2">Live Updates</span>
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <div id="activityStream">
                                <!-- Activity items will be added here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="bx bx-bar-chart me-2"></i>Session Statistics
                        </div>
                        <div class="card-body">
                            <div class="stat-item mb-3">
                                <small class="text-muted">Session Duration</small>
                                <div class="fw-bold text-primary" id="sessionDuration">00:45:23</div>
                            </div>
                            <div class="stat-item mb-3">
                                <small class="text-muted">Pages Visited</small>
                                <div class="fw-bold text-info" id="pagesVisited">7</div>
                            </div>
                            <div class="stat-item mb-3">
                                <small class="text-muted">Actions Performed</small>
                                <div class="fw-bold text-warning" id="actionsCount">23</div>
                            </div>
                            <div class="stat-item mb-3">
                                <small class="text-muted">Idle Time</small>
                                <div class="fw-bold text-secondary" id="idleTime">00:02:15</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Navigation History -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bx bx-history me-2"></i>Navigation History & User Journey
                        </div>
                        <div class="card-body">
                            <div class="navigation-timeline" id="navigationHistory">
                                <!-- Navigation history will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#liveTrackingContent').html(html);
    
    // Start real-time monitoring
    startRealTimeMonitoring(userId, username);
}

function startRealTimeMonitoring(userId, username) {
    // Simulate live page content
    simulateLivePageContent();
    
    // Start activity stream
    startActivityStream();
    
    // Update session statistics
    updateSessionStatistics();
    
    // Start navigation tracking
    startNavigationTracking();
    
    // Set up real-time updates every 2 seconds
    const monitoringInterval = setInterval(() => {
        updateLiveData();
    }, 2000);
    
    // Clear interval when modal is closed
    $('#liveTrackingModal').on('hidden.bs.modal', function () {
        clearInterval(monitoringInterval);
    });
}

function simulateLivePageContent() {
    const pages = [
        { url: '/dashboard', title: 'Dashboard', content: 'User viewing main dashboard with client statistics' },
        { url: '/clients', title: 'Client Management', content: 'Browsing client list and filtering results' },
        { url: '/client/123', title: 'Client Details', content: 'Viewing client profile and adding comments' },
        { url: '/comments', title: 'Comments Section', content: 'Managing client comments and follow-ups' },
        { url: '/reports', title: 'Reports', content: 'Generating and viewing performance reports' }
    ];
    
    const currentPage = pages[Math.floor(Math.random() * pages.length)];
    
    // Update current page display
    document.getElementById('currentPage').textContent = currentPage.title;
    
    // Simulate page content in iframe (this would be the actual user's screen)
    const iframe = document.getElementById('userScreenFrame');
    const pageContent = `
        <html>
            <head>
                <title>${currentPage.title}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
                    .header { background: #007bff; color: white; padding: 15px; margin-bottom: 20px; }
                    .content { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .cursor { position: absolute; width: 20px; height: 20px; background: red; border-radius: 50%; 
                             opacity: 0.7; pointer-events: none; z-index: 1000; animation: blink 1s infinite; }
                    @keyframes blink { 0%, 50% { opacity: 0.7; } 51%, 100% { opacity: 0.3; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>${currentPage.title}</h2>
                    <small>URL: ${currentPage.url}</small>
                </div>
                <div class="content">
                    <p>${currentPage.content}</p>
                    <div style="margin-top: 20px;">
                        <button style="padding: 10px 20px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 4px;">Action Button 1</button>
                        <button style="padding: 10px 20px; margin: 5px; background: #28a745; color: white; border: none; border-radius: 4px;">Action Button 2</button>
                        <button style="padding: 10px 20px; margin: 5px; background: #ffc107; color: white; border: none; border-radius: 4px;">Action Button 3</button>
                    </div>
                </div>
                <!-- Simulated cursor -->
                <div class="cursor" id="liveCursor" style="left: ${Math.floor(Math.random() * 400)}px; top: ${Math.floor(Math.random() * 300)}px;"></div>
            </body>
        </html>
    `;
    
    iframe.srcdoc = pageContent;
}

function startActivityStream() {
    const activities = [
        { action: 'Clicked', target: 'Client Management Button', time: new Date(), type: 'click' },
        { action: 'Navigated', target: 'Dashboard → Client List', time: new Date(), type: 'navigation' },
        { action: 'Typed', target: 'Search field: "John Doe"', time: new Date(), type: 'input' },
        { action: 'Scrolled', target: 'Client list (down 200px)', time: new Date(), type: 'scroll' },
        { action: 'Opened', target: 'Client Details Modal', time: new Date(), type: 'modal' }
    ];
    
    const streamContainer = document.getElementById('activityStream');
    
    activities.forEach((activity, index) => {
        setTimeout(() => {
            addActivityToStream(activity);
        }, index * 3000);
    });
}

function addActivityToStream(activity) {
    const streamContainer = document.getElementById('activityStream');
    const timestamp = new Date().toLocaleTimeString();
    
    const typeColors = {
        click: 'primary',
        navigation: 'success',
        input: 'warning',
        scroll: 'info',
        modal: 'purple'
    };
    
    const activityHTML = `
        <div class="activity-item d-flex align-items-center mb-3 animate-in" style="animation: slideInLeft 0.5s ease;">
            <div class="activity-icon me-3">
                <span class="badge bg-${typeColors[activity.type] || 'secondary'}" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bx ${getActivityIcon(activity.type)}"></i>
                </span>
            </div>
            <div class="activity-details flex-grow-1">
                <div class="fw-bold">${activity.action}: ${activity.target}</div>
                <small class="text-muted">${timestamp}</small>
            </div>
            <div class="activity-time">
                <small class="badge bg-light text-dark">${timestamp}</small>
            </div>
        </div>
    `;
    
    streamContainer.insertAdjacentHTML('afterbegin', activityHTML);
    
    // Remove old activities (keep only last 10)
    const activities = streamContainer.querySelectorAll('.activity-item');
    if (activities.length > 10) {
        activities[activities.length - 1].remove();
    }
}

function getActivityIcon(type) {
    const icons = {
        click: 'bx-mouse',
        navigation: 'bx-navigation',
        input: 'bx-edit',
        scroll: 'bx-mouse-alt',
        modal: 'bx-window'
    };
    return icons[type] || 'bx-circle';
}

function updateSessionStatistics() {
    let sessionSeconds = 0;
    let pagesCount = 1;
    let actionsCount = 0;
    let idleSeconds = 0;
    
    const updateStats = () => {
        sessionSeconds += 1;
        actionsCount += Math.floor(Math.random() * 3);
        
        if (Math.random() > 0.7) {
            pagesCount += 1;
        }
        
        if (Math.random() > 0.8) {
            idleSeconds += 1;
        }
        
        // Update display
        document.getElementById('sessionDuration').textContent = formatTime(sessionSeconds);
        document.getElementById('pagesVisited').textContent = pagesCount;
        document.getElementById('actionsCount').textContent = actionsCount;
        document.getElementById('idleTime').textContent = formatTime(idleSeconds);
    };
    
    setInterval(updateStats, 1000);
}

function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

function startNavigationTracking() {
    const navigationContainer = document.getElementById('navigationHistory');
    const pages = [
        'Dashboard',
        'Client Management',
        'Client Details - John Doe',
        'Add Comment Modal',
        'Client Details - John Doe',
        'Client Management',
        'Reports Section'
    ];
    
    pages.forEach((page, index) => {
        setTimeout(() => {
            addNavigationItem(page, index === pages.length - 1);
        }, index * 4000);
    });
}

function addNavigationItem(pageName, isCurrent = false) {
    const navigationContainer = document.getElementById('navigationHistory');
    const timestamp = new Date().toLocaleTimeString();
    
    const navHTML = `
        <div class="navigation-item d-flex align-items-center mb-2 ${isCurrent ? 'current-page' : ''}">
            <div class="nav-indicator me-3">
                <div class="nav-dot ${isCurrent ? 'bg-success' : 'bg-secondary'}" style="width: 12px; height: 12px; border-radius: 50%;"></div>
            </div>
            <div class="nav-details flex-grow-1">
                <span class="fw-bold ${isCurrent ? 'text-success' : ''}">${pageName}</span>
                ${isCurrent ? '<span class="badge bg-success ms-2">CURRENT</span>' : ''}
            </div>
            <small class="text-muted">${timestamp}</small>
        </div>
    `;
    
    if (isCurrent) {
        // Remove previous current page marker
        const previousCurrent = navigationContainer.querySelector('.current-page');
        if (previousCurrent) {
            previousCurrent.classList.remove('current-page');
            previousCurrent.querySelector('.nav-dot').classList.remove('bg-success');
            previousCurrent.querySelector('.nav-dot').classList.add('bg-secondary');
            previousCurrent.querySelector('.text-success').classList.remove('text-success');
            const badge = previousCurrent.querySelector('.badge');
            if (badge) badge.remove();
        }
    }
    
    navigationContainer.insertAdjacentHTML('beforeend', navHTML);
}

function updateLiveData() {
    // Update mouse position
    const mouseX = Math.floor(Math.random() * 500) + 100;
    const mouseY = Math.floor(Math.random() * 400) + 100;
    document.getElementById('mousePos').textContent = `X: ${mouseX}, Y: ${mouseY}`;
    
    // Randomly add new activity
    if (Math.random() > 0.6) {
        const randomActivities = [
            { action: 'Clicked', target: 'Save Button', type: 'click' },
            { action: 'Typed', target: 'Comment field', type: 'input' },
            { action: 'Scrolled', target: 'Page content', type: 'scroll' },
            { action: 'Hovered', target: 'Menu item', type: 'hover' }
        ];
        
        const randomActivity = randomActivities[Math.floor(Math.random() * randomActivities.length)];
        addActivityToStream(randomActivity);
    }
    
    // Update last click
    const clickTargets = ['Menu Button', 'Client Row', 'Comment Button', 'Status Dropdown', 'Search Field'];
    document.getElementById('lastClick').textContent = clickTargets[Math.floor(Math.random() * clickTargets.length)];
    
    // Simulate page change occasionally
    if (Math.random() > 0.9) {
        simulateLivePageContent();
    }
}

function toggleFullscreen() {
    const screenContainer = document.querySelector('.live-screen-container');
    if (screenContainer.requestFullscreen) {
        screenContainer.requestFullscreen();
    }
}

function takeScreenshot() {
    // Simulate screenshot functionality
    const timestamp = new Date().toLocaleTimeString();
    addActivityToStream({
        action: 'Screenshot Captured',
        target: `Screen capture at ${timestamp}`,
        type: 'screenshot'
    });
    
    // Show success message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="bx bx-check-circle me-2"></i>Screenshot saved successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
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
    
    // Show the filter for callback/no answer clients
    if (status === 'Call Back' || status === 'No Answer') {
        $('#modalCommentsFilter').show();
        clearModalCommentsFilter();
    } else {
        $('#modalCommentsFilter').hide();
    }
    
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
    $('#clientDetailsModal').modal('show');
    $('#clientDetailsModalLabel').text(`${username} - Status Changed Clients - Today's Status Changes (New to No Answer/Callback)`);
    
    // Hide the comments filter for status changes
    $('#modalCommentsFilter').hide();
    
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
            let html = `
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Status Changes: <span class="badge bg-info">${response.total_changed}</span></h6>
                            <p class="text-muted mb-0">Period: ${response.period}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>
                                <span class="badge bg-primary me-1">${response.callback_count} to Callback</span>
                                <span class="badge bg-warning">${response.no_answer_count} to No Answer</span>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
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
                            <td>
                                <div>
                                    <h6 class="mb-0">${client.first_name} ${client.last_name || ''}</h6>
                                    <small class="text-muted">ID: ${client.id}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small class="text-muted d-block">${client.phone1 || 'N/A'}</small>
                                    <small class="text-muted">${client.email || 'N/A'}</small>
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
                                        <strong>Updated:</strong> ${new Date(client.updated_at).toLocaleDateString()} 
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
                        <td colspan="5" class="text-center py-4">
                            <div class="text-muted">No status changes found in the selected period</div>
                        </td>
                    </tr>
                `;
            }
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            $('#clientDetailsContent').html(html);
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
</script>
@endsection

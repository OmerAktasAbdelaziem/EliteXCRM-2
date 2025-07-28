@extends('layouts.app')

@section('style')
    <style>
        .user-stat-card {
            transition: all 0.3s ease;
        }
        .user-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .activity-badge {
            font-size: 0.75rem;
        }
        .client-comment {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px;
            margin-bottom: 8px;
        }
    </style>
@endsection

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
<div class="container-fluid">

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.stats') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="days" class="form-label">Time Period</label>
                            <select name="days" id="days" class="form-select">
                                <option value="1" {{ $days == 1 ? 'selected' : '' }}>Today</option>
                                <option value="3" {{ $days == 3 ? 'selected' : '' }}>Last 3 Days</option>
                                <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select Users</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="border rounded p-2" style="height: 200px; overflow-y: auto;">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="" id="all_users" 
                                                   {{ empty($selectedUserIds) ? 'checked' : '' }} onchange="toggleAllUsers()">
                                            <label class="form-check-label fw-bold text-primary" for="all_users">
                                                All Users
                                            </label>
                                        </div>
                                        <hr class="my-2">
                                        @foreach($allUsers as $user)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input user-checkbox" type="checkbox" 
                                                       value="{{ $user->id }}" id="user_{{ $user->id }}"
                                                       {{ in_array($user->id, $selectedUserIds ?? []) ? 'checked' : '' }}
                                                       onchange="handleUserSelection({{ $user->id }}, '{{ $user->username }}', '{{ $user->first_name }} {{ $user->last_name }}')">
                                                <label class="form-check-label" for="user_{{ $user->id }}">
                                                    {{ $user->username }} ({{ $user->first_name }} {{ $user->last_name }})
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="selectAllUsers()">Select All</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllUsers()">Clear All</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-2" style="height: 200px; overflow-y: auto;">
                                        <h6 class="text-muted mb-2">Selected Users:</h6>
                                        <div id="selectedUsersDisplay">
                                            @if(empty($selectedUserIds))
                                                <span class="text-muted">All Users Selected</span>
                                            @else
                                                @foreach($selectedUserIds as $userId)
                                                    @php $selectedUser = $allUsers->where('id', $userId)->first(); @endphp
                                                    @if($selectedUser)
                                                        <div class="badge bg-soft-primary text-primary me-1 mb-1 p-2" id="selected_{{ $userId }}">
                                                            {{ $selectedUser->username }}
                                                            <button type="button" class="btn-close btn-close-white ms-1" 
                                                                    style="font-size: 0.7rem;" onclick="removeUser({{ $userId }})"></button>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
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
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-search-alt"></i> Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetFilters()">
                                <i class="bx bx-refresh"></i> Reset
                            </button>
                        </div>
                    </form>
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <div class="text-muted">
                                <small>Period: {{ $dateFrom->format('d/m/Y') }} - {{ $dateTo->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        @if(!empty($selectedUserIds))
            <div class="col-12 mb-3">
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i>
                    <strong>Filtered View:</strong> Showing statistics for 
                    @if(count($selectedUserIds) === 1)
                        user: {{ $allUsers->where('id', $selectedUserIds[0])->first()->username ?? 'Unknown' }}
                    @else
                        {{ count($selectedUserIds) }} selected users:
                        @foreach($selectedUserIds as $userId)
                            <span class="badge bg-soft-primary text-primary me-1">{{ $allUsers->where('id', $userId)->first()->username ?? 'Unknown' }}</span>
                        @endforeach
                    @endif
                    <a href="{{ route('user.stats', ['days' => $days]) }}" class="btn btn-sm btn-outline-info ms-2">
                        <i class="bx bx-x"></i> Clear Filter
                    </a>
                </div>
            </div>
        @endif
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-muted mb-2">{{ !empty($selectedUserIds) ? 'Selected Users' : 'Total Users' }}</p>
                            <h4 class="mb-0">{{ count($userStats) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-3">
                                <i class="bx bx-user font-size-24 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-muted mb-2">Total Callbacks</p>
                            <h4 class="mb-0">{{ collect($userStats)->sum('total_callbacks') }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-3">
                                <i class="bx bx-phone font-size-24 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-muted mb-2">Total No Answers</p>
                            <h4 class="mb-0">{{ collect($userStats)->sum('total_no_answers') }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded-3">
                                <i class="bx bx-phone-off font-size-24 text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-muted mb-2">Total Comments (Period)</p>
                            <h4 class="mb-0">{{ collect($userStats)->sum('total_comments_today') }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-3">
                                <i class="bx bx-message-dots font-size-24 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Pipeline</th>
                                    <th class="text-center">Callback Clients</th>
                                    <th class="text-center">No Answer Clients</th>
                                    <th class="text-center">Total Pending</th>
                                    <th class="text-center">Comments ({{ $days }} day{{ $days > 1 ? 's' : '' }})</th>
                                    <th class="text-center">Activity Rate</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userStats as $stat)
                                    @php
                                        $totalPending = $stat['total_callbacks'] + $stat['total_no_answers'];
                                        $activityRate = $totalPending > 0 ? round(($stat['total_comments_today'] / $totalPending) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="text-black mb-0">{{ $stat['user']->username }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info">Pipeline {{ $stat['user']->pipeline_id }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-warning text-warning font-size-14">
                                                {{ $stat['total_callbacks'] }}
                                            </span>
                                            @if($stat['comments_count_callback'] > 0)
                                                <br><small class="text-success">({{ $stat['comments_count_callback'] }} comments)</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-danger text-danger font-size-14">
                                                {{ $stat['total_no_answers'] }}
                                            </span>
                                            @if($stat['comments_count_no_answer'] > 0)
                                                <br><small class="text-success">({{ $stat['comments_count_no_answer'] }} comments)</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <h6 class="mb-0">{{ $totalPending }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-success text-success font-size-14">
                                                {{ $stat['total_comments_today'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($activityRate >= 50)
                                                <span class="badge bg-success">{{ $activityRate }}%</span>
                                            @elseif($activityRate >= 25)
                                                <span class="badge bg-warning">{{ $activityRate }}%</span>
                                            @else
                                                <span class="badge bg-danger">{{ $activityRate }}%</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if($stat['total_callbacks'] > 0)
                                                    <button type="button" class="btn btn-sm btn-soft-warning" 
                                                            onclick="showClientDetails({{ $stat['user']->id }}, 'Call Back', {{ $days }})">
                                                        <i class="bx bx-phone"></i> Callbacks
                                                    </button>
                                                @endif
                                                @if($stat['total_no_answers'] > 0)
                                                    <button type="button" class="btn btn-sm btn-soft-danger" 
                                                            onclick="showClientDetails({{ $stat['user']->id }}, 'No Answer', {{ $days }})">
                                                        <i class="bx bx-phone-off"></i> No Answer
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-info-circle font-size-24 mb-2"></i>
                                                <p>No user statistics available</p>
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
        selectedDisplay.innerHTML = '<span class="text-muted">All Users Selected</span>';
        
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
    const allUsersText = selectedDisplay.querySelector('.text-muted');
    if (allUsersText) {
        allUsersText.remove();
    }
    
    // Create user badge
    const userBadge = document.createElement('div');
    userBadge.className = 'badge bg-soft-primary text-primary me-1 mb-1 p-2';
    userBadge.id = `selected_${userId}`;
    userBadge.innerHTML = `
        ${username}
        <button type="button" class="btn-close btn-close-white ms-1" 
                style="font-size: 0.7rem;" onclick="removeUser(${userId})"></button>
    `;
    
    selectedDisplay.appendChild(userBadge);
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
}

function updateSelectedDisplay() {
    const selectedDisplay = document.getElementById('selectedUsersDisplay');
    const selectedBadges = selectedDisplay.querySelectorAll('.badge');
    
    // If no users are selected, show "All Users Selected"
    if (selectedBadges.length === 0) {
        selectedDisplay.innerHTML = '<span class="text-muted">All Users Selected</span>';
        document.getElementById('all_users').checked = true;
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
            const label = checkbox.nextElementSibling.textContent.trim();
            const username = label.split(' (')[0];
            
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
    selectedDisplay.innerHTML = '<span class="text-muted">All Users Selected</span>';
    hiddenInputs.innerHTML = '';
}

function resetFilters() {
    // Reset to default values
    document.getElementById('days').value = '1';
    clearAllUsers();
}

function showClientDetails(userId, status, days) {
    $('#clientDetailsModal').modal('show');
    $('#clientDetailsModalLabel').text(`${status} Clients Details`);
    
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
        data: { days: days },
        success: function(response) {
            let html = `
                <div class="mb-3">
                    <h6>Status: <span class="badge ${status === 'Call Back' ? 'bg-warning' : 'bg-danger'}">${response.status}</span></h6>
                    <p class="text-muted mb-0">Period: ${response.period}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Contact Info</th>
                                <th class="text-center">Comments in Period</th>
                                <th>Last 3 Comments</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            if (response.clients.length > 0) {
                response.clients.forEach(function(item) {
                    const client = item.client;
                    const commentsCount = item.comments_count_period;
                    
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
                                <span class="badge ${commentsCount > 0 ? 'bg-success' : 'bg-secondary'}">${commentsCount}</span>
                            </td>
                            <td>
                    `;
                    
                    if (client.comments && client.comments.length > 0) {
                        client.comments.forEach(function(comment) {
                            // Use the server-formatted dates instead of JavaScript Date parsing
                            const commentDate = comment.formatted_date || new Date(comment.created_at).toLocaleDateString();
                            const commentTime = comment.formatted_time || new Date(comment.created_at).toLocaleTimeString();
                            html += `
                                <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-primary fw-bold">${comment.user ? comment.user.username : 'Unknown'}</small>
                                        <small class="text-muted">${commentDate} ${commentTime}</small>
                                    </div>
                                    <div class="comment-text" style="white-space: pre-wrap; word-wrap: break-word; line-height: 1.5;">
                                        ${comment.comment}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html += '<div class="text-center py-3"><small class="text-muted">No recent comments</small></div>';
                    }
                    
                    html += `
                            </td>
                        </tr>
                    `;
                });
            } else {
                html += `
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="text-muted">No clients found</div>
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
                    <i class="bx bx-error-circle"></i> Failed to load client details. Please try again.
                </div>
            `);
        }
    });
}
</script>
@endsection

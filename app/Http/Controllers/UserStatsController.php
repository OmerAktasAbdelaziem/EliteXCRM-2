<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Client_comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserStatsController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            abort(403, 'Unauthorized access');
        }

        $days = $request->input('days', 1); // Default to today
        $selectedUserIds = $request->input('user_ids', []); // Optional multiple user filter
        $commentsMin = $request->input('comments_min'); // Comments filter minimum
        $commentsMax = $request->input('comments_max'); // Comments filter maximum
        
        // Handle special case for yesterday
        if ($days === 'yesterday') {
            $dateFrom = Carbon::yesterday()->startOfDay();
            $dateTo = Carbon::yesterday()->endOfDay();
        } else {
            $dateFrom = Carbon::now()->subDays($days - 1)->startOfDay();
            $dateTo = Carbon::now()->endOfDay();
        }

        // Get all users with their basic info (bypass pipeline filtering for this special user)
        $allUsers = User::select('id', 'username', 'first_name', 'last_name', 'email', 'pipeline_id')
            ->where('deleted', 0)
            ->orderBy('username')
            ->get();

        // Filter users if specific users are selected
        $users = !empty($selectedUserIds) ? $allUsers->whereIn('id', $selectedUserIds) : $allUsers;

        $userStats = [];

        foreach ($users as $user) {
            // Get clients with callback/no answer status for this user
            $callbackClients = Client::where('user_id', $user->id)
                ->where('sales_status', 'Call Back')
                ->where('deleted', 0)
                ->with(['comments' => function($query) {
                    $query->latest()->limit(3)->with('user:id,username');
                }])
                ->get();

            $noAnswerClients = Client::where('user_id', $user->id)
                ->where('sales_status', 'No Answer')
                ->where('deleted', 0)
                ->with(['comments' => function($query) {
                    $query->latest()->limit(3)->with('user:id,username');
                }])
                ->get();

            // Count comments made by this user in the selected period for callback clients
            $commentsCountCallback = Client_comment::whereIn('client_id', $callbackClients->pluck('id'))
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count();

            // Count comments made by this user in the selected period for no answer clients
            $commentsCountNoAnswer = Client_comment::whereIn('client_id', $noAnswerClients->pluck('id'))
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count();

            // Total callback/no answer statuses for this user
            $totalCallbacks = $callbackClients->count();
            $totalNoAnswers = $noAnswerClients->count();

            // Get clients with "New" status for this user
            $newClients = Client::where('user_id', $user->id)
                ->where('sales_status', 'New')
                ->where('deleted', 0)
                ->with(['comments' => function($query) {
                    $query->latest()->limit(3)->with('user:id,username');
                }])
                ->get();

            // Get clients that changed status from "New" to "No Answer" or "Call Back" in the selected period
            $statusChangedClients = $this->getStatusChangesForUser($user->id, Carbon::today()->startOfDay(), Carbon::today()->endOfDay());

            // Count total new clients
            $totalNewClients = $newClients->count();

            // Only include users who have callback, no answer, or new clients
            if ($totalCallbacks > 0 || $totalNoAnswers > 0 || $totalNewClients > 0) {
                $userStats[] = [
                    'user' => $user,
                    'callback_clients' => $callbackClients,
                    'no_answer_clients' => $noAnswerClients,
                    'new_clients' => $newClients,
                    'total_callbacks' => $totalCallbacks,
                    'total_no_answers' => $totalNoAnswers,
                    'total_new_clients' => $totalNewClients,
                    'comments_count_callback' => $commentsCountCallback,
                    'comments_count_no_answer' => $commentsCountNoAnswer,
                    'total_comments_today' => $commentsCountCallback + $commentsCountNoAnswer,
                    'status_changed_clients' => $statusChangedClients
                ];
            }
        }

        // Sort by total callback/no answer/new count (descending)
        usort($userStats, function($a, $b) {
            $totalA = $a['total_callbacks'] + $a['total_no_answers'] + $a['total_new_clients'];
            $totalB = $b['total_callbacks'] + $b['total_no_answers'] + $b['total_new_clients'];
            return $totalB - $totalA;
        });

        // Apply comments filter if specified
        if ($commentsMin !== null || $commentsMax !== null) {
            $userStats = array_filter($userStats, function($stat) use ($commentsMin, $commentsMax) {
                $commentCount = $stat['total_comments_today'];
                
                // Check minimum filter
                if ($commentsMin !== null && $commentCount < (int)$commentsMin) {
                    return false;
                }
                
                // Check maximum filter
                if ($commentsMax !== null && $commentCount > (int)$commentsMax) {
                    return false;
                }
                
                return true;
            });
            
            // Reset array keys after filtering
            $userStats = array_values($userStats);
        }

        return view('user_stats.index', compact('userStats', 'days', 'dateFrom', 'dateTo', 'allUsers', 'selectedUserIds', 'commentsMin', 'commentsMax'));
    }

    public function getClientDetails(Request $request, $userId, $status)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            abort(403, 'Unauthorized access');
        }

        $days = $request->input('days', 1);
        
        // Handle special case for yesterday
        if ($days === 'yesterday') {
            $dateFrom = Carbon::yesterday()->startOfDay();
            $dateTo = Carbon::yesterday()->endOfDay();
        } else {
            $dateFrom = Carbon::now()->subDays($days - 1)->startOfDay();
            $dateTo = Carbon::now()->endOfDay();
        }

        // Get all clients with the specified status
        $clients = Client::where('user_id', $userId)
            ->where('sales_status', $status)
            ->where('deleted', 0)
            ->select('id', 'first_name', 'last_name', 'phone1', 'email', 'created_at', 'updated_at')
            ->get();

        // Get the last 3 comments for each client
        $clientIds = $clients->pluck('id');
        $lastComments = [];
        
        // Get the last 3 comments for each client
        foreach ($clientIds as $clientId) {
            $clientComments = Client_comment::where('client_id', $clientId)
                ->with(['user:id,username', 'client:id,first_name,last_name,phone1,email'])
                ->select('id', 'client_id', 'user_id', 'comment', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
                
            foreach ($clientComments as $comment) {
                $comment->formatted_date = $comment->created_at->format('d/m/Y');
                $comment->formatted_time = $comment->created_at->format('H:i:s');
                $comment->formatted_datetime = $comment->created_at->format('d/m/Y H:i:s');
                $lastComments[] = $comment;
            }
        }

        // Get comment counts for the period
        $commentCounts = Client_comment::whereIn('client_id', $clientIds)
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('client_id', DB::raw('COUNT(*) as count'))
            ->groupBy('client_id')
            ->pluck('count', 'client_id');

        $clientsWithCommentCounts = [];
        foreach ($clients as $client) {
            $clientsWithCommentCounts[] = [
                'client' => $client,
                'comments_count_period' => $commentCounts->get($client->id, 0)
            ];
        }

        // Sort clients: those with comments in the period first, then by comment count descending
        usort($clientsWithCommentCounts, function($a, $b) {
            $aHasComments = $a['comments_count_period'] > 0 ? 1 : 0;
            $bHasComments = $b['comments_count_period'] > 0 ? 1 : 0;
            
            // First sort by whether they have comments (those with comments first)
            if ($aHasComments !== $bHasComments) {
                return $bHasComments - $aHasComments;
            }
            
            // Then sort by comment count descending
            return $b['comments_count_period'] - $a['comments_count_period'];
        });

        return response()->json([
            'clients' => $clientsWithCommentCounts,
            'last_comments' => $lastComments,
            'status' => $status,
            'period' => $days . ' day' . ($days > 1 ? 's' : ''),
            'daily_target' => $status === 'No Answer' ? count($clientsWithCommentCounts) * 3 : null
        ]);
    }

    /**
     * Get real-time updates for live dashboard
     */
    public function getLiveUpdates(Request $request)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastUpdate = $request->input('last_update');
        $days = $request->input('days', 1);
        $userIds = $request->input('user_ids', []);
        
        $lastUpdateTime = Carbon::createFromTimestamp($lastUpdate / 1000);
        
        // Check for new comments since last update
        $newCommentsQuery = Client_comment::where('created_at', '>', $lastUpdateTime)
            ->with(['user:id,username', 'client:id,first_name,last_name,sales_status']);
        
        // Filter by user IDs if provided
        if (!empty($userIds)) {
            $newCommentsQuery->whereIn('user_id', $userIds);
        }
        
        $newComments = $newCommentsQuery->get()
            ->map(function($comment) {
                return [
                    'user' => $comment->user->username ?? 'Unknown',
                    'client' => ($comment->client->first_name ?? 'Unknown') . ' ' . ($comment->client->last_name ?? ''),
                    'comment' => substr($comment->comment, 0, 80) . (strlen($comment->comment) > 80 ? '...' : ''),
                    'sales_status' => $comment->client ? ($comment->client->sales_status ?? 'New') : 'New',
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s')
                ];
            });
        
        // Check for new clients with callback/no answer status
        $newCallbacksQuery = Client::where('sales_status', 'Call Back')
            ->where('updated_at', '>', $lastUpdateTime);
        
        $newNoAnswersQuery = Client::where('sales_status', 'No Answer')
            ->where('updated_at', '>', $lastUpdateTime);
        
        // Filter by user IDs if provided
        if (!empty($userIds)) {
            $newCallbacksQuery->whereIn('user_id', $userIds);
            $newNoAnswersQuery->whereIn('user_id', $userIds);
        }
        
        $newCallbacks = $newCallbacksQuery->count();
        $newNoAnswers = $newNoAnswersQuery->count();
        
        $hasUpdates = $newComments->count() > 0 || $newCallbacks > 0 || $newNoAnswers > 0;
        
        return response()->json([
            'has_updates' => $hasUpdates,
            'updates' => [
                'new_comments' => $newComments,
                'new_callbacks' => $newCallbacks > 0 ? [['count' => $newCallbacks]] : [],
                'new_no_answers' => $newNoAnswers > 0 ? [['count' => $newNoAnswers]] : [],
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Get daily target progress for all users
     */
    public function getDailyTargetProgress(Request $request)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $selectedUserIds = $request->input('user_ids', []);
        $today = Carbon::today();
        
        // Get all users or selected users
        $users = !empty($selectedUserIds) 
            ? User::whereIn('id', $selectedUserIds)->where('deleted', 0)->get()
            : User::where('deleted', 0)->get();

        $targetProgress = [];

        foreach ($users as $user) {
            // Count "No Answer" clients for this user
            $noAnswerClientsCount = Client::where('user_id', $user->id)
                ->where('sales_status', 'No Answer')
                ->where('deleted', 0)
                ->count();

            // Count comments made today by this user on "No Answer" clients
            $commentsToday = Client_comment::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->whereHas('client', function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->where('sales_status', 'No Answer')
                          ->where('deleted', 0);
                })
                ->count();

            $dailyTarget = $noAnswerClientsCount * 3; // 3 comments per "No Answer" client
            $targetProgress[] = [
                'user_id' => $user->id,
                'username' => $user->username,
                'no_answer_clients' => $noAnswerClientsCount,
                'comments_today' => $commentsToday,
                'daily_target' => $dailyTarget,
                'target_progress' => $dailyTarget > 0 ? round(($commentsToday / $dailyTarget) * 100, 1) : 0,
                'remaining_comments' => max(0, $dailyTarget - $commentsToday)
            ];
        }

        return response()->json([
            'target_progress' => $targetProgress,
            'date' => $today->format('Y-m-d')
        ]);
    }

    /**
     * Get clients that changed status from "New" to "No Answer" or "Call Back" in the specified period (optimized for today)
     */
    private function getStatusChangesForUser($userId, $dateFrom, $dateTo)
    {
        // Get only NEW clients (created today) that have their status changed to "No Answer" or "Call Back"
        // This ensures we only get clients that were:
        // 1. Created today (new clients)
        // 2. Had their status changed from "New" to either "No Answer" or "Call Back"
        $newClientsChangedToday = Client::where('user_id', $userId)
            ->whereIn('sales_status', ['No Answer', 'Call Back'])
            ->where('deleted', 0)
            ->whereBetween('created_at', [$dateFrom, $dateTo]) // Created today
            ->where('updated_at', '>', DB::raw('created_at')) // Status was updated after creation (meaning it was changed from "New")
            ->select('id', 'first_name', 'last_name', 'phone1', 'sales_status', 'created_at', 'updated_at')
            ->get();

        $result = [
            'new_to_no_answer' => $newClientsChangedToday->where('sales_status', 'No Answer'),
            'new_to_callback' => $newClientsChangedToday->where('sales_status', 'Call Back'),
            'total_changed' => $newClientsChangedToday->count(),
            'no_answer_count' => $newClientsChangedToday->where('sales_status', 'No Answer')->count(),
            'callback_count' => $newClientsChangedToday->where('sales_status', 'Call Back')->count()
        ];

        return $result;
    }

    public function getStatusChangedClients(Request $request, $userId)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            abort(403, 'Unauthorized access');
        }

        // Always check for today's status changes only
        $dateFrom = Carbon::today()->startOfDay();
        $dateTo = Carbon::today()->endOfDay();

        // Get status changed clients for this user
        $statusChanges = $this->getStatusChangesForUser($userId, $dateFrom, $dateTo);

        // Get detailed client information
        $allChangedClients = $statusChanges['new_to_no_answer']->merge($statusChanges['new_to_callback']);

        return response()->json([
            'clients' => $allChangedClients->values(),
            'total_changed' => $statusChanges['total_changed'],
            'no_answer_count' => $statusChanges['no_answer_count'],
            'callback_count' => $statusChanges['callback_count'],
            'period' => 'Today (' . $dateFrom->format('d/m/Y') . ')'
        ]);
    }

    public function transferClients(Request $request)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'required|integer|exists:clients,id',
            'target_user_id' => 'required|integer|exists:users,id'
        ]);

        $clientIds = $request->input('client_ids');
        $targetUserId = $request->input('target_user_id');

        try {
            // Check if target user exists and is not deleted
            $targetUser = User::where('id', $targetUserId)
                ->where('deleted', 0)
                ->first();

            if (!$targetUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Target user not found or is inactive.'
                ], 400);
            }

            // Get clients to transfer
            $clients = Client::whereIn('id', $clientIds)
                ->where('deleted', 0)
                ->get();

            if ($clients->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid clients found for transfer.'
                ], 400);
            }

            $transferredCount = 0;
            $transferredClients = [];

            // Update each client's user_id
            foreach ($clients as $client) {
                $oldUserId = $client->user_id;
                $client->user_id = $targetUserId;
                $client->updated_at = now();
                
                if ($client->save()) {
                    $transferredCount++;
                    $transferredClients[] = [
                        'id' => $client->id,
                        'name' => $client->first_name . ' ' . $client->last_name,
                        'old_user_id' => $oldUserId,
                        'new_user_id' => $targetUserId
                    ];

                    // Add a comment to record the transfer
                    Client_comment::create([
                        'client_id' => $client->id,
                        'user_id' => Auth::id(), // Current user who performed the transfer
                        'comment' => "Client transferred from user ID {$oldUserId} to user ID {$targetUserId} ({$targetUser->username}) by " . Auth::user()->username,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully transferred {$transferredCount} client(s) to {$targetUser->username}",
                'transferred_count' => $transferredCount,
                'transferred_clients' => $transferredClients,
                'target_user' => [
                    'id' => $targetUser->id,
                    'username' => $targetUser->username,
                    'name' => $targetUser->first_name . ' ' . $targetUser->last_name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLatestNotification(Request $request)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Get the latest notification from client comments (as our notification source)
            $latestComment = Client_comment::with(['user', 'client'])
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$latestComment) {
                return response()->json([
                    'latest_notification' => null,
                    'total_count' => 0
                ]);
            }

            // Get total count of recent notifications (last 24 hours)
            $totalCount = Client_comment::where('created_at', '>=', Carbon::now()->subDay())->count();

            $clientStatus = $latestComment->client ? $latestComment->client->sales_status : 'Unknown';
            
            $notification = [
                'id' => $latestComment->id,
                'type' => 'Comment',
                'title' => 'New Comment Added',
                'message' => $latestComment->comment,
                'user_name' => $latestComment->user ? $latestComment->user->username : 'Unknown',
                'client_name' => $latestComment->client ? 
                    $latestComment->client->first_name . ' ' . $latestComment->client->last_name : 'Unknown',
                'client_status' => $clientStatus,
                'formatted_time' => $latestComment->created_at->diffForHumans(),
                'is_read' => false
            ];

            return response()->json([
                'latest_notification' => $notification,
                'total_count' => $totalCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'latest_notification' => null,
                'total_count' => 0,
                'error' => 'Failed to load notification'
            ], 500);
        }
    }

    public function getNotifications(Request $request)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $limit = $request->input('limit', 10);
            
            // Get recent comments as notifications
            $comments = Client_comment::with(['user', 'client'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            $notifications = [];

            foreach ($comments as $comment) {
                $clientStatus = $comment->client ? $comment->client->sales_status : 'Unknown';
                
                $notifications[] = [
                    'id' => $comment->id,
                    'type' => 'Comment',
                    'title' => 'New Comment Added',
                    'message' => $comment->comment,
                    'user_name' => $comment->user ? $comment->user->username : 'Unknown',
                    'client_name' => $comment->client ? 
                        $comment->client->first_name . ' ' . $comment->client->last_name : 'Unknown',
                    'client_status' => $clientStatus,
                    'formatted_time' => $comment->created_at->diffForHumans(),
                    'created_at' => $comment->created_at->toISOString(),
                    'is_read' => $comment->created_at < Carbon::now()->subHours(2) // Mark as read if older than 2 hours
                ];
            }

            // Add some system notifications for demo
            if (count($notifications) < $limit) {
                $systemNotifications = $this->getSystemNotifications($limit - count($notifications));
                $notifications = array_merge($notifications, $systemNotifications);
            }

            return response()->json([
                'notifications' => $notifications,
                'total_count' => count($notifications)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'notifications' => [],
                'total_count' => 0,
                'error' => 'Failed to load notifications'
            ], 500);
        }
    }

    private function getSystemNotifications($limit)
    {
        $systemNotifications = [];
        
        // Get recent status changes as notifications
        $recentStatusChanges = Client::where('updated_at', '>=', Carbon::now()->subDays(2))
            ->whereIn('sales_status', ['Call Back', 'No Answer'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();

        foreach ($recentStatusChanges as $client) {
            $systemNotifications[] = [
                'id' => 'status_' . $client->id,
                'type' => 'Status Change',
                'title' => 'Client Status Updated',
                'message' => "Client status changed to {$client->sales_status}",
                'user_name' => $client->user ? $client->user->username : 'System',
                'client_name' => $client->first_name . ' ' . $client->last_name,
                'client_status' => $client->sales_status,
                'formatted_time' => $client->updated_at->diffForHumans(),
                'created_at' => $client->updated_at->toISOString(),
                'is_read' => true
            ];
            
            if (count($systemNotifications) >= $limit) {
                break;
            }
        }

        return $systemNotifications;
    }
}

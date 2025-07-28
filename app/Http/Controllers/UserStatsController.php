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
        $dateFrom = Carbon::now()->subDays($days - 1)->startOfDay();
        $dateTo = Carbon::now()->endOfDay();

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

            // Only include users who have callback or no answer clients
            if ($totalCallbacks > 0 || $totalNoAnswers > 0) {
                $userStats[] = [
                    'user' => $user,
                    'callback_clients' => $callbackClients,
                    'no_answer_clients' => $noAnswerClients,
                    'total_callbacks' => $totalCallbacks,
                    'total_no_answers' => $totalNoAnswers,
                    'comments_count_callback' => $commentsCountCallback,
                    'comments_count_no_answer' => $commentsCountNoAnswer,
                    'total_comments_today' => $commentsCountCallback + $commentsCountNoAnswer
                ];
            }
        }

        // Sort by total callback/no answer count (descending)
        usort($userStats, function($a, $b) {
            $totalA = $a['total_callbacks'] + $a['total_no_answers'];
            $totalB = $b['total_callbacks'] + $b['total_no_answers'];
            return $totalB - $totalA;
        });

        return view('user_stats.index', compact('userStats', 'days', 'dateFrom', 'dateTo', 'allUsers', 'selectedUserIds'));
    }

    public function getClientDetails(Request $request, $userId, $status)
    {
        // Check if user is authorized (only user 298274)
        if (Auth::id() !== 298274) {
            abort(403, 'Unauthorized access');
        }

        $days = $request->input('days', 1);
        $dateFrom = Carbon::now()->subDays($days - 1)->startOfDay();
        $dateTo = Carbon::now()->endOfDay();

        $clients = Client::where('user_id', $userId)
            ->where('sales_status', $status)
            ->where('deleted', 0)
            ->select('id', 'first_name', 'last_name', 'phone1', 'email', 'created_at')
            ->with(['comments' => function($query) {
                $query->latest()->limit(3)->with('user:id,username')->select('id', 'client_id', 'user_id', 'comment', 'created_at');
            }])
            ->get();

        // Format comment dates properly for JavaScript
        $clients->each(function($client) {
            $client->comments->each(function($comment) {
                $comment->formatted_date = $comment->created_at->format('d/m/Y');
                $comment->formatted_time = $comment->created_at->format('H:i:s');
                $comment->formatted_datetime = $comment->created_at->format('d/m/Y H:i:s');
            });
        });

        $clientsWithCommentCounts = [];

        // Get all comment counts in one query
        $clientIds = $clients->pluck('id');
        $commentCounts = Client_comment::whereIn('client_id', $clientIds)
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('client_id', DB::raw('COUNT(*) as count'))
            ->groupBy('client_id')
            ->pluck('count', 'client_id');

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
            'status' => $status,
            'period' => $days . ' day' . ($days > 1 ? 's' : '')
        ]);
    }
}

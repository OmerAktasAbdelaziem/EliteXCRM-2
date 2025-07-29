<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserStatsController;

/*
|--------------------------------------------------------------------------
| Debugging Routes for UTF-8 Issues
|--------------------------------------------------------------------------
*/

// Temporary debugging route - remove after fixing the issue
Route::get('/debug-utf8-user/{userId}', function($userId) {
    if (auth()->id() !== 298274) {
        abort(403);
    }
    
    $problematicData = [];
    
    // Check clients for user 298274
    $clients = \App\Models\Client::where('user_id', $userId)
        ->select('id', 'first_name', 'last_name', 'email', 'phone1')
        ->get();
    
    foreach ($clients as $client) {
        $fields = ['first_name', 'last_name', 'email', 'phone1'];
        foreach ($fields as $field) {
            $value = $client->{$field};
            if ($value && !mb_check_encoding($value, 'UTF-8')) {
                $problematicData[] = [
                    'type' => 'client',
                    'id' => $client->id,
                    'field' => $field,
                    'value' => $value,
                    'encoding' => mb_detect_encoding($value),
                    'hex' => bin2hex($value)
                ];
            }
        }
    }
    
    // Check comments for user 298274
    $comments = \App\Models\Client_comment::where('user_id', $userId)
        ->select('id', 'client_id', 'comment')
        ->limit(100) // Limit to avoid timeout
        ->get();
    
    foreach ($comments as $comment) {
        if ($comment->comment && !mb_check_encoding($comment->comment, 'UTF-8')) {
            $problematicData[] = [
                'type' => 'comment',
                'id' => $comment->id,
                'client_id' => $comment->client_id,
                'field' => 'comment',
                'value' => substr($comment->comment, 0, 100) . '...',
                'encoding' => mb_detect_encoding($comment->comment),
                'hex' => substr(bin2hex($comment->comment), 0, 50) . '...'
            ];
        }
    }
    
    return response()->json([
        'user_id' => $userId,
        'problematic_data_count' => count($problematicData),
        'problematic_data' => $problematicData
    ]);
})->middleware('auth');

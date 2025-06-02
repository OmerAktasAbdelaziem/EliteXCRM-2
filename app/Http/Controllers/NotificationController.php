<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function mark_all_as_read()
    {
        $notifications = Client::where('user_id', Auth::user()->id)->where('is_notified', 1);

        $notifications->update(['is_notified' => 0]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}

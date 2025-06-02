<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Chat_ah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function store(Request $request,$id)
    {
        $inputs = $request->only('message');
        $inputs['client_id'] = $id;
        $inputs['user_id'] = auth()->id();
        Chat_ah::create($inputs);
        Action::create([
            'client_id' => $id,
            'user_id'   => Auth::id(),
            'text'      => 'Added new Chat <span class="text-primary">' . $inputs['message'] . '</span>'
        ]);
        session()->flash('success', 'Chat message has been sent successfully.');

        return response()->json([
            'success' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $chat = Chat_ah::findOrFail($id);

        $request->validate([
            'message' => ['required' , 'string'],
        ]);

        $chat_text = $request->input('message');

        if ($chat->message != $request->message) {
            Action::create([
                'client_id' => $chat->client_id,
                'user_id'   => Auth::id(),
                'text'      => 'Updated Chat <span class="text-success">' . $chat_text . '</span>'
            ]);
        }

        $chat->message = $chat_text;

        $chat->save();

        session()->flash('success', 'Chat updated successfully.');

        return response()->json([
            'success' => true,
        ]);
    }

    public function delete($id)
    {
        $chat = Chat_ah::findOrFail($id);

        $action = Action::create([
            'client_id' => $chat->client_id,
            'user_id'   => Auth::id(),
            'text'      => 'deleted Chat <span class="text-danger">' . $chat->message . '</span>'
        ]);

        Log::channel('telegram')->info(Auth::user()->username.' '.$action->text.' On '.$action->client->first_name.' '.$action->client->last_name);
        
        $chat->delete();

        session()->flash('success', 'Chat deleted successfully.');

        return response()->json([
            'success' => true,
        ]);
    }
}

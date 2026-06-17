<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Client;
use App\Models\Client_comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Client_CommentController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'comment' => ['required' , 'string'],
        ]);

        $comment = $request->input('comment');
    
        $clientComment = new Client_comment();

        $clientComment->comment = $comment;
        $clientComment->user_id = Auth::id();
        $clientComment->client_id = $id;

        $clientComment->save();
        $client->touch();
        Action::create([
            'client_id' => $id,
            'user_id'   => Auth::id(),
            'text'      => 'Added new Comment <span class="text-primary">' . $comment . '</span>'
        ]);

        if ($request->from_index == 1) {
            return redirect()->back()->with('success','Comment has been Added successfully.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'Comment has been Added successfully.');

            return response()->json([
                'success' => true,
                ]);
        }
        return redirect()->back()->with('success','Comment has been Added successfully.');

    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $comment = Client_comment::findOrFail($id);
        return view('client.comment-edit',[
            'comment' => $comment
        ]);
    }

    public function update(Request $request, $id)
    {
        $comment = Client_comment::findOrFail($id);

        $request->validate([
            'comment' => ['required' , 'string'],
        ]);

        $comment_text = $request->input('comment');

        if ($comment->comment != $request->comment) {
            Action::create([
                'client_id' => $comment->client_id,
                'user_id'   => Auth::id(),
                'text'      => 'Updated Comment <span class="text-success">' . $comment_text . '</span>'
            ]);
        }

        $comment->comment = $comment_text;

        $comment->save();

        $comment->client->touch();

        session()->flash('success', 'Comment updated successfully.');

        return response()->json([
            'success' => true,
        ]);
    }

    public function delete($id)
    {
        $comment = Client_comment::findOrFail($id);

        $action = Action::create([
            'client_id' => $comment->client_id,
            'user_id'   => Auth::id(),
            'text'      => 'deleted Comment <span class="text-danger">' . $comment->comment . '</span>'
        ]);

        Log::channel('telegram')->info(Auth::user()->username.' '.$action->text.' On '.$action->client->first_name.' '.$action->client->last_name);
        
        $comment->delete();

        session()->flash('success', 'Comment deleted successfully.');

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy($id)
    {
        //
    }

    public function list($id)
    {
        $comments = Client_comment::where('client_id', $id)->with('user')->latest()->get();

        $commentsData = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->toDateTimeString(),
                'user' => [
                    'first_name' => $comment->user->first_name,
                    'username' => $comment->user->username,
                ],
            ];
        });
    
        return response()->json($commentsData);
    }
}

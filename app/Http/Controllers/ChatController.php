<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Message};
use App\Events\MessageSent;

class ChatController extends Controller
{
    //
    public function index()
    {
        $user = User::where('id',auth()->user()->id)->get();
        return view('chat.index', compact('user'));
    }

    // fetch messages between authenticated user and $user
    public function fetch(User $user)
    {
        $me = auth()->id();
        $messages = Message::where(function ($q) use ($me, $user) {
            $q->where('from_id', $me)->where('to_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('from_id', $user->id)->where('to_id', $me);
        })->with('sender')->orderBy('created_at')->get();

        return response()->json($messages);
    }

    // send message to $user
    public function send(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $message = Message::create([
            'from_id' => auth()->id(),
            'to_id' => $user->id,
            'body' => $request->body,
        ]);

        // fire event (broadcasts to both participants)
        event(new MessageSent($message));

        return response()->json($message->load('sender'));
    }
}

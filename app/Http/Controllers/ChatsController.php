<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Inertia\Inertia ;
use Inertia\Response;
use App\Models\User;
use App\Events\MessageSent;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): Response
    {
        return Inertia::render('discussions/Chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request, User $user)
    {
        $message = $user -> messages() -> create([
            'message' => $request -> message,
            'user_id' => $user -> user_id,
        ]);

        broadcast(new MessageSent(auth()->user(), $message))->toOthers();

//        return ['status' => 'Message Sent!'];
    }
}

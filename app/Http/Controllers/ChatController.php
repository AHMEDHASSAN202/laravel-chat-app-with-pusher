<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    function index()
    {
        return view('chat.index');
    }

    public function messages(Request $request)
    {
        $receiveID = $request->filled('receive_id') ? $request->get('receive_id') : null;
        if (is_null($receiveID)) return Response::json([], 404);
        return Response::json(Chat::messages(auth()->id(), $receiveID));
    }

    public function addMessage(Request $request)
    {
        $message = $request->get('message', null);
        $receive_id = $request->get('receive_id', null);
        if (!($message && $receive_id)) {
            return Response::json([], 401);
        }
        $message = Chat::create([
            'message'   => $message,
            'user_id_1' => Auth::id(),
            'user_id_2' => $receive_id
        ]);
        broadcast(new MessageSent(Auth::user(), $message));
        return Response::json(['status' => true], 200);
    }
}
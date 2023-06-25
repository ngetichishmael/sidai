<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
   public function index()
   {
      dd("nothing");
      $users = User::with('latestMessage')->get();

      return view('app/chat/chat-list', compact('users'));
   }

   public function show($id)
   {
      $user = User::findOrFail($id);
      $messages = $user->messages()->orderBy('created_at')->get();

      return response()->json([
         'user' => $user,
         'messages' => $messages,
      ]);
   }

   public function store(Request $request)
   {
      // Create a new message
      $message = new Message();
      $message->sender_id = auth()->id();
      $message->receiver_id = $request->input('receiver_id');
      $message->content = $request->input('content');
      $message->save();

      // Dispatch event/notification for new message

      return response()->json($message, 201);
   }

   public function reply(Request $request, $id)
   {
      $message = Message::findOrFail($id);
      $reply = new Message();
      $reply->sender_id = auth()->id();
      $reply->receiver_id = $message->sender_id;
      $reply->content = $request->input('content');
      $reply->save();

      // Dispatch event/notification for new reply

      return response()->json($reply, 201);
   }
}

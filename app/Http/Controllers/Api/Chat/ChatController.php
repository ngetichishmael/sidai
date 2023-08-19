<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
   public function index()
   {
      $chats = Chat::where('receiver_id', auth()->id())
         ->orderBy('created_at', 'desc')
         ->get();

      return response()->json($chats);
   }

   public function markAsRead($id)
   {
      $chat = Chat::findOrFail($id);
      $chat->is_read = true;
      $chat->save();

      return response()->json(['message' => 'Chat marked as read']);
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

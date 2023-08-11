<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage
{
   use Dispatchable, InteractsWithSockets, SerializesModels;

   public $chat;

   public function __construct($chat)
   {
      $this->chat = $chat;
   }

   public function broadcastOn()
   {
      return new PrivateChannel("chat.{$this->chat->receiver_id}");
   }

   public function broadcastAs()
   {
      return 'NewChatMessage';
   }
}

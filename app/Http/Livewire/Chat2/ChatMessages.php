<?php

namespace App\Http\Livewire\Chat2;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatMessages extends Component
{
   public $receiverId;
   public $messages;

   public function mount($receiverId)
   {
      $this->receiverId = $receiverId;
      $this->messages = Chat::where(function ($query) {
         $query->where('sender_id', Auth::id())
            ->where('receiver_id', $this->receiverId);
      })->orWhere(function ($query) {
         $query->where('sender_id', $this->receiverId)
            ->where('receiver_id', Auth::id());
      })->get();
   }

   public function sendMessage($message)
   {
      Chat::create([
         'sender_id' => Auth::id(),
         'receiver_id' => $this->receiverId,
         'message' => $message
      ]);

      $this->reset('message');
   }

   public function render()
   {
      return view('livewire.chat.chat-messages');
   }
}

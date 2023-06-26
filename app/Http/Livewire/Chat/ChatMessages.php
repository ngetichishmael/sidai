<?php

namespace App\Http\Livewire\Chat;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class ChatMessages extends Component
{
public $user;
public $messages;
public $replyContent;

public function mount($id)
{
   $this->user = User::findOrFail($id);
   $this->messages = $this->user->messages()->orderBy('created_at')->get();
}

public function render()
{
   return view('livewire.chat.chat-messages', [
      'user' => $this->user,
      'messages' => $this->messages,
   ]);
}

public function reply()
{
   // Reply to a message
   $reply = new Message();
   $reply->sender_id = auth()->id();
   $reply->receiver_id = $this->user->id;
   $reply->content = $this->replyContent;
   $reply->save();

   // Dispatch event/notification for new reply

   // Clear the reply input field
   $this->replyContent = '';

   // Refresh the messages
   $this->messages = $this->user->messages()->orderBy('created_at')->get();
}
}

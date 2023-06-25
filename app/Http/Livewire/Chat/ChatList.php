<?php

namespace App\Http\Livewire\Chat;

use App\Models\User;
use Livewire\Component;

class ChatList extends Component
{

   public function render()
   {
      $users = User::with('latestMessage')->get();

      return view('livewire.chat.chat-list', [
         'users' => $users,
      ]);
   }
}

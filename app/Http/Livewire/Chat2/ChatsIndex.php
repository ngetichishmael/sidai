<?php

namespace App\Http\Livewire\Chat2;

use Livewire\Component;

class ChatsIndex extends Component
{
   public $chats;

   public function mount()
   {
      $this->chats = auth()->user()->chats()->orderBy('created_at', 'desc')->get();
   }

   public function render()
   {
      return view('livewire.chat.chats-index');
   }
}

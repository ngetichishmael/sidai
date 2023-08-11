<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;

class CreateChat extends Controller
{
   public function index()
   {
      return view('livewire.chat.create-chat');
   }
}

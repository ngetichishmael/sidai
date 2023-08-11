<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CreateChat extends Controller
{
   public function index()
   {
      return view('livewire.chat.create-chat');
   }
}

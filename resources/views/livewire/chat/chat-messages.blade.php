@extends('layouts.app')
@section('title','Chat Messages')
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> Chat Messages </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Chats</a></li>
                     <li class="breadcrumb-item active">Messages</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div>
      @foreach ($messages as $message)
         <div>
            @if ($message->sender_id === Auth::id())
               <div class="sent">{{ $message->message }}</div>
            @else
               <div class="received">{{ $message->message }}</div>
            @endif
         </div>
      @endforeach

      <input type="text" wire:model="message">
      <button wire:click="sendMessage">Send</button>
   </div>
@endsection

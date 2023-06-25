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
      <h2>{{ $user->name }}</h2>
      <div>
         <div>
            @foreach ($messages as $message)
               <div>
                  <p>{{ $message->content }}</p>
                  <p>{{ $message->created_at }}</p>
               </div>
            @endforeach
         </div>
         <div>
            <input type="text" wire:model="replyContent">
            <button wire:click="reply">Reply</button>
         </div>
      </div>
   </div>
@endsection

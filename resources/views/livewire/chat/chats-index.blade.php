@extends('layouts.app')
@section('title','Chats')
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> Chats </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                     <li class="breadcrumb-item active"><a href="#">Chats</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>

<div>
   @foreach ($chats as $chat)
      <div>
         <img src="{{ $chat->receiver->image }}" alt="User Image">
         <span>{{ $chat->receiver->name }}</span>
         <span>{{ $chat->created_at->diffForHumans() }}</span>
         <button wire:click="openChat({{ $chat->receiver_id }})">Read Messages</button>
      </div>
   @endforeach
</div>
@endsection

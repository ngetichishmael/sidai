@extends('layouts.app')
@section('title','Chats')
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> ChatS </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Chats</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div>
      @livewire('chat.chat-list')
@endsection
{{-- page scripts --}}
@section('script')

@endsection

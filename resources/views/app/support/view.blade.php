@extends('layouts.app')

@section('title', 'Reply Chats')

@section('content')
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div style="color: {{ $ticket->status == 'open' ? 'orangered' : 'green' }}">
           <h2> The current state of the issues is  {{ $ticket->status == 'open' ? 'open' : 'closed' }}</h2>
            </div>
            <div style="margin-left: 90%; width: fit-content; height: fit-content">
            <a href="{!! route('support.update', ['id' => $ticket->id]) !!}" class="btn btn-warning">{{ $ticket->status == 'open' ? 'Close it' : 'Closed' }}</a>
            </div>
            <br/>
            <div class="card">
               <div class="card-header">{{ $ticket->subject }}</div>

               <div class="card-body" id="message-list">
                  @foreach ($ticket->messages as $message)
                     <div class="message {{ $message->sender_code === auth()->user()->user_code ? 'sent' : 'received' }}">
                        @if ($message->sender_code !== auth()->user()->user_code)
                           <div class="user-profile-pic">
                              <img src="{{ $user->image ?? 'image' }}" alt="{{ $user->name ?? 'image' }}">
                           </div>
                           <hr/>
                        @endif
                        <div class="message-content">
                           <div class="message-text">{{ $message->message }}</div>
                           <div class="message-time">{{ $message->created_at->diffForHumans() }}</div>
                        </div>

                     </div>
                  @endforeach

               </div>

               <div class="card-footer">
                  <form id="message-form" method="POST" action="{{ route('support.reply', [$ticket->id, $message->id]) }}">
                     @csrf
                     <div class="form-group row">
                        <label for="message" class="col-md-4 col-form-label text-md-right">{{ __('Reply') }}</label>

                        <div class="col-md-6">
                           <textarea id="message-input" class="form-control @error('message') is-invalid @enderror" name="message" required autocomplete="message"></textarea>

                           @error('message')
                           <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                           @enderror
                        </div>
                     </div>

                     <div class="form-group row mb-1">
                        <div class="col-md-6 mt-1 offset-md-8">
                           <button type="submit" class="btn btn-primary">
                              {{ __('Send') }}
                           </button>
                        </div>
                        <br/>
                        <br/>
                        <div style="margin-left: 80%; width: fit-content; height: fit-content">
                           <a href="{!! route('support.update', ['id' => $ticket->id]) !!}" class="btn btn-warning">{{ $ticket->status == 'open' ? 'Close it' : 'Closed' }}</a>
{{--                           <a href="{!! route('support.update', ['id' => $ticket->id]) !!}" class="btn btn-warning">--}}
{{--                              <i style="color: white" data-feather="{{ $ticket->status == 'open' ? 'circle' : 'circle' }}" ></i>--}}
{{--                              {{ $ticket->status == 'open' ? 'Close it' : 'Closed' }}--}}
{{--                           </a>--}}

                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div style="margin-left:30%">
            <a href="{!! route('support.index') !!}" class="btn btn-flat-info btn-outline-info">BACK</a>
         </div>
      </div>
   </div>
@endsection

@section('script')
   <script>
      // send message when form is submitted
      const form = document.querySelector('form');
      form.addEventListener('submit', event => {
         event.preventDefault();
         const messageInput = document.querySelector('#message');
         const message = messageInput.value.trim();
         if (message) {
            sendMessage(message);
            messageInput.value = '';
         }
      });

      const messageList = document.getElementById('message-list');
      const messageForm = document.getElementById('message-form');
      const messageInput = document.getElementById('message-input');

      // Connect to the Socket.IO server
      const socket = io();

      // Listen for incoming messages
      socket.on('message', data => {
         const messageContent = `
            <div class="message">
               <div class="message-content ${data.sender_code === '{{ auth()->user()->user_code }}' ? 'right' : 'left'}">
                  ${data.sender_code !== '{{ auth()->user()->user_code }}' ? '<div class="user-icon"><i class="fas fa-user"></i></div><div class="user-name">' +
            sender_name + '</div>' : ''}
            data.sender_name + '</div>' : ''}
<div class="message-text">${data.message}</div>
<div class="message-time">${moment(data.created_at).fromNow()}</div>
</div>
</div>
`;
         $('.card-body').append(messageContent);
      });
   </script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.min.js"></script>

<style>
   .message {
      display: flex;
      align-items: flex-end;
      margin-bottom: 10px;
   }

   .message.sent .message-content {
      background-color: #0C102A;
      color: #fff;
      border-radius: 10px;
      padding-left: 10px;
      padding-top: 10px;
      padding-right: 10px;
      align-content: center;
      margin-left: auto;
   }

   .message.received .message-content {
      background-color: #f5f5f5;
      color: #0C102A;
      border-radius: 10px;
      padding-left: 10px;
      padding-top: 10px;
      padding-right: 10px;
      align-content: center;
      margin-right: auto;
   }

   .message-text {
      font-family: Arial, sans-serif;
      font-size: 16px;
      margin-bottom: 5px;
   }

   .message-time {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #999;
   }

   .user-profile-pic {
      width: 30px;
      height: 30px;
      margin-right: 10px;
      border-radius: 50%;
      overflow: hidden;
   }

   .user-profile-pic img {
      width: 50%;
      height: 50%;
      object-fit: cover;
      object-position: center;
   }

</style>
<script src="{{ mix('js/app.js') }}"></script>
@livewireScripts

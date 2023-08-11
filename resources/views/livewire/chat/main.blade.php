@extends('layouts.app')
@section('title','Chat Messages')
@section('style')
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
   @stack('scripts')
@endsection
@section('script')
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

   <script src="{{ asset('js/app.js') }}" defer></script>
   <style>

      body{

         font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      }
   </style>
@endsection
@section('content')
   <div>
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> Chat | Messages </h2>
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
      <div class="chat_container">
        <div class="chat_list_container">

            @livewire('chat.chat-list')

        </div>

        <div class="chat_box_container">

            @livewire('chat.chatbox')

            @livewire('chat.send-message')
        </div>
    </div>


    <script>
        window.addEventListener('chatSelected', event => {

            if (window.innerWidth < 768) {

                $('.chat_list_container').hide();
                $('.chat_box_container').show();

            }

            $('.chatbox_body').scrollTop($('.chatbox_body')[0].scrollHeight);
        let height= $('.chatbox_body')[0].scrollHeight;
    //alert(height);
    window.livewire.emit('updateHeight',{

height:height,


    });
        });


        $(window).resize(function() {

            if (window.innerWidth > 768) {
                $('.chat_list_container').show();
                $('.chat_box_container').show();

            }

        });


        $(document).on('click', '.return', function() {

            $('.chat_list_container').show();
            $('.chat_box_container').hide();


        });
    </script>

<script>
    // //let el= $('#chatBody');
    // let el = document.querySelector('#chatBody');
    // window.addEventListener('scroll', (event) => {
    //     // handle the scroll event
    //     alert('aasd');

    // });
    $(document).on('scroll','#chatBody',function() {
        // alert('aasd');

        var top = $('.chatbox_body').scrollTop();
        if (top == 0) {

            window.livewire.emit('loadmore');
        }


    });

    </script>
</div>
@endsection

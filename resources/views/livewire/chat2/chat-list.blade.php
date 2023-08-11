<div>
   @foreach ($users as $user)
      <div>
         <img src="{{ $user->avatar }}" alt="User Avatar">
         <h3>{{ $user->name }}</h3>
         <p>Last message: {{ $user->latestMessage->content }}</p>
         <button wire:click="showMessages({{ $user->id }})">Read Messages</button>
      </div>
   @endforeach
</div>
@endsection

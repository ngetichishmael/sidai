// require('./bootstrap');
import './bootstrap';

require('./bootstrap');

window.Echo.private(`chat.${userId}`)
   .listen('NewChatMessage', (event) => {
      Livewire.emit('messageReceived', event.chat);
   });

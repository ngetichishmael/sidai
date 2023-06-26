<?php

namespace App\Models;

use App\Events\NewChatMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;

class Chat extends Model
{
    use HasFactory;
   protected $fillable = ['sender_id', 'receiver_id', 'message'];

   public static function boot()
   {
      parent::boot();

      static::created(function ($chat) {
         $event = new \stdClass();
         $event->chat = $chat;
         Broadcast::privateChannel("chat.{$chat->receiver_id}")->event(new NewChatMessage($event));
      });
   }

   public function receiver()
   {
      return $this->belongsTo(User::class, 'receiver_id');
   }

}

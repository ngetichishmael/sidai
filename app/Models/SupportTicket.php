<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
   use HasFactory;

   protected $guarded=[];
   public function Customer(): BelongsTo
   {
      return $this->belongsTo(User::class , 'customer_code','user_code');
   }
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class , 'user_code', 'user_code');
   }
   public function messages()
   {
      return $this->hasMany(Message::class, 'ticket_id');
   }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $guarded=[''];

   public function sender()
   {
      return $this->belongsTo(User::class, 'user_code');
   }

   public function receiver()
   {
      return $this->belongsTo(User::class, 'user_code');
   }

}

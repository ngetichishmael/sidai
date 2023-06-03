<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_log extends Model
{
    Protected $table = 'activity_log';
    protected $guarded = [''];
   public function user()
   {
      return $this->belongsTo(User::class, 'user_code', 'user_code');
   }
}

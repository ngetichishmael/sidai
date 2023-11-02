<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_payments extends Model
{
   protected $table = 'order_payments';
   protected $guarded = [""];
   public function user() {
      return $this->belongsTo(User::class);
   }

}

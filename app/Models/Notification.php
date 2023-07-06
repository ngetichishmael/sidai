<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table='notifications';
   protected $guarded=[""];
//   protected static function boot()
//   {
//      parent::boot();
//
//      static::created(function ($notification) {
//         // Queue the notification using Laravel's queue system
//         dispatch(new SendNotificationJob($notification));
//      });
//   }

}

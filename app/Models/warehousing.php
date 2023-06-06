<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\products\product_information;

class warehousing extends Model
{
   use Searchable;
   protected $table = 'warehouse';
   protected $guarded = [''];
   protected $searchable = [
      'name',
      'country',
      'city',
      'location',
      'phone_number',
      'email'
   ];
   public function manager()
   {
      return $this->belongsTo(User::class ,'shop_attendee', 'user_code');
   }

   public function productInformation()
   {
      return $this->hasMany(product_information::class, 'warehouse_code', 'warehouse_code');
   }
}

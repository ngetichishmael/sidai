<?php

namespace App\Models\suppliers;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class suppliers extends Model
{
   use Notifiable;
   protected $table = 'suppliers';
   protected $guarded = [""];
   protected $primaryKey = 'id';
   /**
    * Get all of the Orders for the suppliers
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Orders(): HasMany
   {
      return $this->hasMany(Orders::class, 'SupplierID', 'id');
   }
   public function OrdersDelivered(): HasMany
   {
      return $this->hasMany(Orders::class, 'SupplierID', 'id')->where('order_status','Complete Delivery');
   }
   
}

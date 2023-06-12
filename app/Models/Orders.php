<?php

namespace App\Models;

use App\Models\suppliers\suppliers;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Orders extends Model
{
   use Searchable;
   protected $table = 'orders';

   protected $guarded = [];
   protected $searchable = [
      'user.name',
      'Customer.customer_name',
      'order_type',
      'Customer.Region.name',
   ];
   /**
    * Get the OrderItem associated with the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function OrderItem(): HasOne
   {
      return $this->hasOne(Order_items::class, 'order_code', 'order_code');
   }

   /**
    * Get all of the OrderItems for the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function OrderItems(): HasMany
   {
      return $this->hasMany(Order_items::class, 'order_code', 'order_code');
   }
   /**
    * Get the User that owns the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function User(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_code', 'user_code');
   }
   /**
    * Get the Customer that owns the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Customer(): BelongsTo
   {
      return $this->belongsTo(customers::class, 'customerID', 'id');
   }
   public function distributor(): BelongsTo
   {
      return $this->belongsTo(suppliers::class, 'supplierID', 'id');
   }
}

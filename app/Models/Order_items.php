<?php

namespace App\Models;

use App\Models\products\product_information;
use App\Models\products\product_price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order_items extends Model
{
   protected $table = 'order_items';
   protected $guarded = [];

   /**
    * Get the Order that owns the Order_items
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Order(): BelongsTo
   {
      return $this->belongsTo(Orders::class, 'order_code', 'order_code');
   }
   /**
    * Get the ProductPrice associated with the product_information
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function ProductPrice(): HasOne
   {
      return $this->hasOne(product_price::class, 'productID', 'productID');
   }
   public function productInformation(): BelongsTo
   {
      return $this->belongsTo(product_information::class, 'productID', 'id');
   }
}

<?php

namespace App\Models\products;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\Relations\HasOne;

class product_information extends Model
{
   protected $table = 'product_information';
   protected $guarded = [];

   /**
    * Get the ProductPrice associated with the product_information
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function ProductPrice(): HasOne
   {
      return $this->hasOne(product_price::class, 'productID', 'id');
   }
}
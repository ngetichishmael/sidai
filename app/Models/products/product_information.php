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
   /**
    * Get the Inventory associated with the product_information
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function Inventory(): HasOne
   {
      return $this->hasOne(product_inventory::class, 'productID', 'id');
   }
   public function ProductSKU()
   {
      return $this->hasMany(ProductSku::class, 'sku_code','sku_code');
   }
}

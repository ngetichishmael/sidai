<?php

namespace App\Models\products;

use App\Models\warehousing;
use Auth;
use Illuminate\Database\Eloquent\Model;

class product_information extends Model
{
   protected $table = 'product_information';
   protected $guarded = [];

   /**
    * Get the ProductPrice associated with the product_information
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function ProductPrice()
   {
      return $this->hasOne(product_price::class, 'productID', 'id');
   }
   /**
    * Get the Inventory associated with the product_information
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function Inventory()
   {
      return $this->hasOne(product_inventory::class, 'productID', 'id');
   }
   public function ProductSKU()
   {
      return $this->hasMany(ProductSku::class, 'sku_code','sku_code');
   }
   public function warehouse()
   {
      return $this->belongsTo(warehousing::class, 'warehouse_code','warehouse_code');
   }
}

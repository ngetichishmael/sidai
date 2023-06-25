<?php

namespace App\Models\suppliers;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class suppliers extends Model
{
   protected $table = 'suppliers';
   protected $guarded = [""];
   /**
    * Get all of the Orders for the suppliers
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Orders(): HasMany
   {
      return $this->hasMany(Orders::class, 'SupplierID', 'id');
   }
}

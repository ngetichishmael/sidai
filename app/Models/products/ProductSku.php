<?php

namespace App\Models\products;

use App\Models\User;
use App\Models\warehousing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    use HasFactory;
   protected $guarded =[];
   public function Inventory()
   {
      return $this->belongsTo(product_inventory::class, 'productID', 'id');
   }
   public function addedBy()
   {
      return $this->belongsTo(User::class,  'added_by', 'user_code');
   }
   public function restockedBy()
   {
      return $this->belongsTo(User::class,  'restocked_by', 'user_code');
   }
}

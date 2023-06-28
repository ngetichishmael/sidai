<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Models\products\product_information;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
      'region.name',
      'subregion.name',
   ];
   /**
    * Get all of the Products for the warehousing
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Products(): HasMany
   {
      return $this->hasMany(product_information::class, 'warehouse_code', 'warehouse_code');
   }
   public function Manager()
   {
      return $this->belongsTo(User::class, 'manager', 'user_code');
   }
   public function region()
   {
      return $this->belongsTo(Region::class);
   }
   public function subregion()
   {
      return $this->belongsTo(Subregion::class, 'subregion_id', 'id');
   }
   public function productInformation()
   {
      return $this->hasMany(product_information::class, 'warehouse_code', 'warehouse_code');
   }
}

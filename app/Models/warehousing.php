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
      'region.name',
      'subregion.name',
   ];
   public function manager()
   {
      return $this->belongsTo(User::class ,'manager', 'user_code');
   }
   public function region()
   {
      return $this->belongsTo(Region::class ,'region_id', 'id' );
   }
   public function subregion()
   {
      return $this->belongsTo(Subregion::class ,'subregion_id', 'id' );
   }
   public function productInformation()
   {
      return $this->hasMany(product_information::class, 'warehouse_code', 'warehouse_code');
   }
}

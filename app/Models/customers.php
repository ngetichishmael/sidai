<?php

namespace App\Models;

use App\Models\customer\checkin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class customers extends Model
{
   use searchable;
   protected $searchable = [
      'Area.name',
      'customer_name',
      'phone_number',
      'address',
      'Area.Subregion.name',
      'Area.Subregion.Region.name',
   ];
   protected $table = 'customers';
   /**
    * Get the Region that owns the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Region(): BelongsTo
   {
      return $this->belongsTo(Region::class, 'route_code', 'id');
   }
   /**
    * Get the Creator associated with the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function Creator(): HasOne
   {
      return $this->hasOne(User::class, 'user_code', 'created_by');
   }
   /**
    * Get the Area that owns the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Area(): BelongsTo
   {
      return $this->belongsTo(Area::class, 'route_code', 'id');
   }
}

<?php

namespace App\Models;

use App\Models\customer\checkin;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
   // Relationship with orders
   public function orders()
   {
      return $this->hasMany(Orders::class, 'customerID', 'id');
   }
//   public function number_visited()
//   {
//      return $this->hasMany(Checkin::class, 'customer_id', 'id')->select(\DB::raw('count(*) as counts'))->groupBy('customer_id');
//   }

   public function number_visited()
   {
      return $this->hasOne(Checkin::class, 'customer_id', 'id')
         ->selectRaw('count(*) as count')
         ->groupBy('customer_id');
   }

   public function Checkings(): HasMany
   {
      return $this->hasMany(checkin::class, 'customer_id', 'id');
   }
   public function orderItems()
   {
      return $this->hasManyThrough(Order_items::class, Orders::class, 'customerID', 'order_code', 'id', 'order_code');
   }
   protected $table = 'customers';
   /**
    * Get the Region that owns the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Region(): BelongsTo
   {
      return $this->belongsTo(Region::class, 'region_id', 'id');
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

<?php

namespace App\Models;

use App\Models\customer\checkin;
use App\Traits\Regional;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class customers extends Model
{
   use searchable, Regional;
   protected $table = 'customers';
   protected $guarded = [
      ''
   ];
   protected $searchable = [
      'Area.name',
      'customer_name',
      'phone_number',
      'address',
      'Area.Subregion.name',
      'Area.Subregion.Region.name',
      'Area.Subregion.Region.name'
   ];
//   protected $regional = [
//      'Area.Subregion.Region.name',
//   ];
   // Relationship with orders
   public function orders()
   {
      return $this->hasMany(Orders::class, 'customerID', 'id');
   }
   public function getLastOrderDateAttribute()
   {
      return $this->orders()
         ->latest('created_at')
         ->pluck('created_at')
         ->first();
   }
   public function lastOrderDate()
   {
      return $this->hasMany(Orders::class, 'customerID', 'id')
         ->latest('created_at')
         ->pluck('created_at')
         ->first();
   }

   public function number_visited()
   {
      return $this->hasMany(Checkin::class, 'customer_id', 'id')
         ->select(DB::raw('customer_id, count(*) as counts'))
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
   /**
    * Get the Region that owns the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Region(): BelongsTo
   {
      return $this->belongsTo(Region::class, 'region_id', 'id');
   }public function Subregion(): BelongsTo
   {
      return $this->belongsTo(Region::class, 'subregion_id', 'id');
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
      return $this->belongsTo(Area::class, 'route', 'id');
   }
   public function Wallet(): HasOne
    {
        return $this->hasOne(EWallet::class, 'customer_id', 'id');
    }
}

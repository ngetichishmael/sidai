<?php

namespace App\Models;

use App\Models\suppliers\suppliers;
use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Orders extends Model
{
   use Searchable;
   protected $table = 'orders';

   protected $guarded = [];
   protected $searchable = [
      'user.name',
      'Customer.customer_name',
      'order_type',
      'Customer.Region.name',
   ];
   /**
    * Get the OrderItem associated with the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function OrderItem(): HasOne
   {
      return $this->hasOne(Order_items::class, 'order_code', 'order_code');
   }


   /**
    * Get all of the OrderItems for the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function OrderItems(): HasMany
   {
      return $this->hasMany(Order_items::class, 'order_code', 'order_code');
   }
   /**
    * Get the Supplier that owns the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Supplier(): BelongsTo
   {
      return $this->belongsTo(User::class, 'supplierID', 'id');
   }
   /**
    * Get the User that owns the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function User(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_code', 'user_code');
   }
   /**
    * Get the Customer that owns the Orders
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Customer(): BelongsTo
   {
      return $this->belongsTo(customers::class, 'customerID', 'id');
   }
   public function distributor(): BelongsTo
   {
      return $this->belongsTo(suppliers::class, 'supplierID', 'id');
   }
   public function distributorOrderDelivered(): BelongsTo
   {
      return $this->belongsTo(suppliers::class, 'supplierID', 'id')->where('order_status','Complete Delivery');
   }

   public function scopeToday($query)
   {
      $query->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
   }
   public function scopeYesterday($query)
   {
      $query->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()]);
   }
   public function scopeCurrentWeek($query)
   {
      $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
   }
   public function scopeLastWeek($query)
   {
      $query->whereBetween('created_at', [Carbon::now()->subWeek(1), Carbon::now()->startOfWeek()]);
   }
   public function scopeCurrentMonth($query)
   {
      $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
   }
   public function scopeLastMonth($query)
   {
      $query->whereBetween('created_at', [Carbon::now()->subMonth(1), Carbon::now()->startOfMonth()]);
   }
   public function scopePeriod($query, $start = null, $end = null)
   {
      if ($start === $end && $start !== null) {
         $query->whereLike(['created_at'], (string)$start);
      } else {
         $monthStart = Carbon::now()->startOfMonth()->format('Y-m-d');
         $monthEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
         $from = $start == null ? $monthStart : $start;
         $to = $end == null ? $monthEnd : $end;
         $query->whereBetween('created_at', [$from, $to]);
      }
   }
}

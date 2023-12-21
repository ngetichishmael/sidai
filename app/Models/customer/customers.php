<?php

namespace App\Models\customer;

use App\Models\Area;
use App\Models\Delivery_items;
use App\Models\Order_items;
use App\Models\Region;
use App\Models\Subregion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class customers extends Model
{
   protected $table = 'customers';
   protected $guarded = [];

   /**
    * Get the Area that owns the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Area(): BelongsTo
   {
      return $this->belongsTo(Area::class, 'route_code', 'id');
   }
   public function subregion(): BelongsTo
   {
      return $this->belongsTo(Subregion::class, 'subregion_id', 'id');
   } public function region(): BelongsTo
   {
      return $this->belongsTo(Region::class, 'region_id', 'id');
   }
   public function customers()
   {
      return $this->hasMany(Order_items::class);
   }
   /**
    * Get all of the delivery_items for the customers
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function delivery_items(): HasMany
   {
      return $this->hasMany(Delivery_items::class, 'customer', 'local_key');
   }
   public function scopeToday($query)
   {
      $query->where('created_at', Carbon::today());
   }
   public function scopeYesterday($query)
   {
      $query->where('created_at', Carbon::yesterday());
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

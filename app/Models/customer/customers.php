<?php

namespace App\Models\customer;

use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

   public function scopeToday($query)
   {
      $query->where('updated_at', Carbon::today());
   }
   public function scopeYesterday($query)
   {
      $query->where('updated_at', Carbon::yesterday());
   }
   public function scopeCurrentWeek($query)
   {
      $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
   }
   public function scopeCurrentMonth($query)
   {
      $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
   }
   public function scopePeriod($query, $start = null, $end = null)
   {
      if ($start === $end && $start !== null) {
         $query->whereLike(['updated_at'], (string)$start);
      } else {
         $monthStart = Carbon::now()->startOfMonth()->format('Y-m-d');
         $monthEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
         $from = $start == null ? $monthStart : $start;
         $to = $end == null ? $monthEnd : $end;
         $query->whereBetween('updated_at', [$from, $to]);
      }
   }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Routes extends Model
{
   protected $table = 'routes';
   protected $guarded = [];
   /**
    * Get all of the RouteSales for the Routes
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function RouteSales(): HasMany
   {
      return $this->hasMany(Route_sales::class, 'routeID', 'route_code');
   }
   public function subregion()
   {
      return $this->belongsTo(Subregion::class, 'subregion_id');
   }
   public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
   public function username(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }
}

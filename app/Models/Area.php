<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
   use HasFactory;
   protected $guarded = [""];

   /**
    * Get the Subregion that owns the Area
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Subregion(): BelongsTo
   {
      return $this->belongsTo(Subregion::class);
   }
   /**
    * Get all of the Subarea for the Area
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Subarea(): HasMany
   {
      return $this->hasMany(Subarea::class);
   }
   public function customers()
{
    return $this->hasMany(customers::class);
}
}

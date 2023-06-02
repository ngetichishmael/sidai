<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
   use HasFactory;
   protected $table = 'regions';
   protected $guarded = [];

   /**
    * Get all of the Subregion for the Region
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Subregion(): HasMany
   {
      return $this->hasMany(Subregion::class);
   }
   public function parent()
    {
        return $this->belongsTo(Region::class, 'primary_key');
    }
}

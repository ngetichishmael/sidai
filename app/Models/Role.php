<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];
   public function Subregion(): BelongsTo
   {
      return $this->belongsTo(Subregion::class, 'access_to_id', 'id');
   }
   public function Region(): BelongsTo
   {
      return $this->belongsTo(Region::class, 'access_to_id', 'id');
   }
   public function Area(): BelongsTo
   {
      return $this->belongsTo(Area::class, 'access_to_id', 'id');
   }
   public function  UpdatedBy(): BelongsTo
   {
      return $this->belongsTo(User::class, 'updated_by', 'id');
   }
   public function  CreatedBy(): BelongsTo
   {
      return $this->belongsTo(User::class, 'created_by', 'id');
   }

}

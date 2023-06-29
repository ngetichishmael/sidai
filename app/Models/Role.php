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
   public function checkedPlatforms()
   {
      $platforms = [];

      if ($this->sales_app === 'yes') {
         $platforms[] = 'Sales App';
      }

      if ($this->managers_app === 'yes') {
         $platforms[] = 'Managers App';
      }

      if ($this->manager_dashboard === 'yes') {
         $platforms[] = 'Manager Dashboard';
      }

      if ($this->shop_attendee_dashboard === 'yes') {
         $platforms[] = 'Shop Attendee Dashboard';
      }

      if ($this->admin === 'yes') {
         $platforms[] = 'Admin';
      }

      return implode(', ', $platforms);
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

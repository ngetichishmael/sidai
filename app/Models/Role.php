<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Models\LaratrustRole;

class Role extends Model
{
    public $guarded = [];
   use SoftDeletes;
   protected $dates = [
      'created_at',
      'updated_at',
      'deleted_at',
   ];

   protected $fillable = [
      'name',
      'description',
      'updated_by',
      'data_access_level',
      'created_at',
      'updated_at',
      'deleted_at',
   ];
   public function permissions()
   {
      return $this->belongsToMany(Permission::class);
   }
   public function dataAccessLevels()
   {
      return $this->hasMany(RoleDataAccess::class);
   }
   public function users()
   {
      return $this->belongsToMany(User::class);
   }

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
      return $this->belongsTo(User::class, 'updated_by', 'user_code');
   }
   public function  CreatedBy(): BelongsTo
   {
      return $this->belongsTo(User::class, 'created_by', 'user_code');
   }

}

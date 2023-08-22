<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $guarded = [];
   public $table = 'permissions';

   protected $dates = [
      'created_at',
      'updated_at',
      'deleted_at',
   ];

   protected $fillable = [
      'name',
      'created_at',
      'updated_at',
      'deleted_at',
   ];

   public function roles()
   {
      return $this->belongsToMany(Role::class);
   }

}

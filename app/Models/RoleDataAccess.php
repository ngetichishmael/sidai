<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleDataAccess extends Model
{
    use HasFactory;
    protected $table='role_data_access';
   protected $fillable = ['data_access_level'];

   public function role()
   {
      return $this->belongsTo(Role::class);
   }
}

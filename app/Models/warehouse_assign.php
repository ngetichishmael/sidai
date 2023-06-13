<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class warehouse_assign extends Model
{
    use HasFactory;
    protected $guarded=[''];
    protected $table='warehouse_assigns';

   public function managers()
   {
      return $this->belongsTo(User::class ,'manager', 'user_code' );
   }
   public function user()
   {
      return $this->belongsTo(User::class ,'created_by', 'user_code' );
   }
   public function updatedBy()
   {
      return $this->belongsTo(User::class ,'updated_by', 'user_code' );
   }
}

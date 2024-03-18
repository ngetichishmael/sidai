<?php

namespace App\Models;

use App\Models\suppliers\suppliers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorOrderApproval extends Model
{
    use HasFactory;
    protected $guarded=[''];
   protected $table = 'distributor_order_approvals';

   public function order()
   {
      return $this->belongsTo(Orders::class, 'order_code', 'order_code');
   }

   public function distributor()
   {
      return $this->belongsTo(suppliers::class, 'distributor');
   }
   public function admin()
   {
      return $this->belongsTo(User::class, 'admin_id', 'id');
   }
   public function manager()
   {
      return $this->belongsTo(User::class, 'manager_id', 'id');
   }
}

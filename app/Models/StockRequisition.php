<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequisition extends Model
{
    use HasFactory;
    protected $table = "stock_requisitions";
   protected $fillable = ['sales_person', 'requisition_date', 'status'];
   public function requisitionProducts()
   {
      return $this->hasMany(RequisitionProduct::class, 'requisition_id');
   }
}

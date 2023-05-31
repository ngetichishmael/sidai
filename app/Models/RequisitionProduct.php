<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionProduct extends Model
{
    use HasFactory;
   protected $fillable = ['product_id', 'requisition_id', 'quantity'];
   public function stockRequisition()
   {
      return $this->belongsTo(StockRequisition::class, 'requisition_id');
   }
}

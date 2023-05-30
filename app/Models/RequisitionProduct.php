<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionProduct extends Model
{
    use HasFactory;
    protected $guarded=[''];

   public function stockRequisition()
   {
      return $this->belongsTo(StockRequisition::class, 'requisition_id');
   }
}

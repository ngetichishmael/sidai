<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockRequisition extends Model
{
   use HasFactory;
   protected $table = "stock_requisitions";
   protected $guarded = [''];
   /**
    * Get all of the RequisitionProducts for the StockRequisition
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function RequisitionProducts(): HasMany
   {
      return $this->hasMany(RequisitionProduct::class, 'requisition_id');
   }
   public function ApprovedRequisitionProducts(): HasMany
   {
      return $this->hasMany(RequisitionProduct::class, 'requisition_id')->where('approval', 1);
   }
   /**
    * Get the user that owns the StockRequisition
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_id');
   }
}

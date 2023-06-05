<?php

namespace App\Models;

use App\Models\products\product_information;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitionProduct extends Model
{
    use HasFactory;
   protected $guarded = [''];
   /**
    * Get the ProductInformation that owns the RequisitionProduct
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function ProductInformation(): BelongsTo
   {
       return $this->belongsTo(product_information::class);
   }
   /**
    * Get the StockRequisition that owns the RequisitionProduct
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function StockRequisition(): BelongsTo
   {
       return $this->belongsTo(StockRequisition::class);
   }
}

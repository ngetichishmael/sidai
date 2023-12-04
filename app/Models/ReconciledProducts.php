<?php

namespace App\Models;

use App\Models\products\product_information;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconciledProducts extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    protected $table = 'reconciled_products';
    protected $fillable = [
        'productID',
        'amount',
        'userCode',
        'supplierID',
       'reconciliation_code'
    ];
   public function productInformation() {
      return $this->belongsTo(product_information::class, 'productID', 'id');
   }
}

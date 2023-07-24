<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconciledProducts extends Model
{
    use HasFactory;
    protected $table = 'reconciled_products';
    protected $fillable = [
        'productID',
        'amount',
        'userCode',
        'supplierID'

    ];
    
}

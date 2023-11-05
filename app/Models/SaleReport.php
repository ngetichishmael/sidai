<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReport extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected $casts = [
        'likely_ordered_products' => 'json',
        'highest_sale_products' => 'json',
    ];
}

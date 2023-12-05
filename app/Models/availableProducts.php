<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class availableProducts extends Model
{
    use HasFactory;

    protected $table = 'sales_available_products';

    protected $guarded = [''];

    public function report() {
        return $this->belongsTo(FormResponse::class, 'report_id');
    }
}

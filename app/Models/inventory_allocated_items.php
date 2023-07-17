<?php

namespace App\Models;

use App\Models\products\product_information;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class inventory_allocated_items extends Model
{
    use HasFactory;
    protected $table = "inventory_allocated_items";

    public function inventoryAlloated(): BelongsTo
    {
        return $this->belongsTo(InventoryAllocated::class);
    }
    public function productInformation(): HasOne
    {
        return $this->hasOne(product_information::class, 'id', 'product_code');
    }
}

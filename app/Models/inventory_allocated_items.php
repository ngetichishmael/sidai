<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class inventory_allocated_items extends Model
{
    use HasFactory;
    protected $table = "inventory_allocated_items";

    public function inventoryAlloated(): BelongsTo
   {
      return $this->belongsTo(InventoryAllocated::class);
   }
}

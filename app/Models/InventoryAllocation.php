<?php

namespace App\Models;

use App\Models\suppliers\suppliers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAllocation extends Model
{
    use HasFactory;
    protected $table = 'inventory_allocations';

    public function items(): HasMany
   {
      return $this->hasMany(inventory_allocated_items::class, 'allocation_code','allocation_code');
   }
   public function distributors()
   {
      return $this->belongsTo(suppliers::class, 'distributor', 'id');
   }

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAllocation extends Model
{
    use HasFactory;
    protected $table = 'inventory_allocated_items';
}

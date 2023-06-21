<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Model;

class items extends Model
{
   protected $table = 'inventory_allocated_items';
   protected $guarded = [];
}

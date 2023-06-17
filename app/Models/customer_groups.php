<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_groups extends Model
{
   use HasFactory;
   protected $guarded = [""];
   protected $table = 'customer_groups';
}

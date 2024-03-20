<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitForm extends Model
{
    use HasFactory;
   protected $guarded = [];

   protected $casts = [
      'fields' => 'array',
   ];
}

<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class warehousing extends Model
{
   use Searchable;
   protected $table = 'warehouse';
   protected $guarded = [''];
   protected $searchable = [
      'name',
      'country',
      'city',
      'location',
      'phone_number',
      'email'
   ];
}

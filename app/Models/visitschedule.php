<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class visitschedule extends Model
{
    Protected $table = 'visitschedule';
    protected $fillable = [
        'user_code',
        'shopID',
        'Type',
        'Date',
    ];

}

<?php
namespace App\Models\survey;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class survey extends Model
{
   protected $table = 'survey';

   /**
    * Get all of the comments for the survey
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Questions()
   {
       return $this->hasMany(questions::class, 'survey_code', 'code');
   }

   /**
    * Get all of the comments for the survey
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
    */
   public function answers()
   {
       return $this->hasOneThrough(answers::class,
                                   questions::class,
                                 'survey_code',
                                 'id','code','survey_code');
   }
//  mechanics ->survey primary key -> code
//    id - integer
//    name - string

// cars ->question->questionID
//    id - integer
//    model - string
//    mechanic_id - integer

// owners ->answers->id
//    id - integer
//    name - string
//    car_id - integer

//    /**
//      * Get the car's owner.
//      */
//     public function carOwner()
//     {
//         return $this->hasOneThrough(
//             Owner::class,
//             Car::class,
//             'mechanic_id', // Foreign key on the cars table...
//             'car_id', // Foreign key on the owners table...
//             'id', // Local key on the mechanics table...
//             'id' // Local key on the cars table...
//         );
//     }
   public function scopePeriod($query, $start = null, $end = null)
   {
      if ($start === $end && $start !== null) {
         $query->whereLike(['updated_at'], (string)$start);
      } else {
         $monthStart = Carbon::now()->startOfMonth()->format('Y-m-d');
         $monthEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
         $from = $start == null ? $monthStart : $start;
         $to = $end == null ? $monthEnd : $end;
         $query->whereBetween('updated_at', [$from, $to]);
      }
   }
}

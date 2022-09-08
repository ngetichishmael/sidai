<?php
namespace App\Models\survey;

use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
   protected $table = 'survey_questions';
   /**
    * Get the user that owns the questions
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Survey()
   {
       return $this->belongsTo(survey::class, 'code', 'survey_code');
   }
}

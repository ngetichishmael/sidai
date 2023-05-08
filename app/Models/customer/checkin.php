<?php

namespace App\Models\customer;

use App\Models\User;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class checkin extends Model
{
   use Searchable;
   protected $table = 'customer_checkin';
   public $guarded = [];

   protected $searchable = [
      'User.name',
      'User.Region.name',
      'Customer.customer_name'
   ];
   /**
    * Get the user that owns the checkin
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_code', 'user_code');
   }
   /**
    * Get the Customer that owns the checkin
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Customer(): BelongsTo
   {
      return $this->belongsTo(customers::class, 'customer_id', 'id');
   }

   /**
    * Get the Selfcustomer that owns the checkin
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Self(): BelongsTo
   {
      return $this->belongsTo(customers::class, 'customer_id', 'id')->where('checkin_type', 'self');
   }
   /**
    * Get the Selfcustomer that owns the checkin
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Imprompt(): BelongsTo
   {
      return $this->belongsTo(customers::class, 'customer_id', 'id')->whereNull('checkin_type');
   }
   /**
    * Get the Selfcustomer that owns the checkin
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Admin(): BelongsTo
   {
      return $this->belongsTo(customers::class, 'customer_id', 'id')->where('checkin_type', 'admin');
   }
}

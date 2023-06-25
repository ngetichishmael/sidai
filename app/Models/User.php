<?php

namespace App\Models;

use App\Models\customer\checkin;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{

   use HasFactory, Notifiable;
   use LaratrustUserTrait;
   use HasApiTokens;

   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $guarded = [""];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
   protected $casts = [
      'email_verified_at' => 'datetime',
   ];


   public function sentChats()
   {
      return $this->hasMany(Chat::class, 'sender_id');
   }
   use Notifiable;

   // Define the relationships with other models
   public function messages()
   {
      return $this->hasMany(Message::class, 'sender_id');
   }

   public function latestMessage()
   {
      return $this->hasOne(Message::class, 'sender_id')->latest();
   }
   public function receivedChats()
   {
      return $this->hasMany(Chat::class, 'receiver_id');
   }
   /**
    * Get all of the Targets for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function TargetSales(): HasMany
   {
      return $this->hasMany(SalesTarget::class, 'user_code', 'user_code');
   }
   /**
    * Get all of the TargetLeads for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function TargetLeads(): HasMany
   {
      return $this->hasMany(LeadsTargets::class, 'user_code', 'user_code');
   }
   /**
    * Get all of the TargetsOrder for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function TargetsOrder(): HasMany
   {
      return $this->hasMany(OrdersTarget::class, 'user_code', 'user_code');
   }
   /**
    * Get all of the TargetsVisit for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function TargetsVisit(): HasMany
   {
      return $this->hasMany(VisitsTarget::class, 'user_code', 'user_code');
   }
   /**
    * Get the Region that owns the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function Region(): BelongsTo
   {
      return $this->belongsTo(Region::class,  'route_code', 'id');
   }
   public function Subregion(): BelongsTo
   {
      return $this->belongsTo(Subregion::class,  'route_code', 'id');
   }
   public function Checkings(): HasMany
   {
      return $this->hasMany(checkin::class, 'user_code', 'user_code');
   }
   public function Orders(): HasMany
   {
      return $this->hasMany(Orders::class, 'user_code', 'user_code');
   }
   public function PendingOrders(): HasMany
   {
      return $this->hasMany(Orders::class, 'user_code', 'user_code');
   }
   public function countPendingOrders(): int
   {
      return $this->PendingOrders()->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Derivery', 'Waiting acceptance'])->count();
   }

   /**
    * Get all of the Customers for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function Customers(): HasMany
   {
      return $this->hasMany(customers::class, 'user_code', 'user_code');
   }
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

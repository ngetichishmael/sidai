<?php

namespace App\Models;

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

   public function Users(): HasMany
   {
      return $this->hasMany(User::class, 'account_type','account_type');
   }
}

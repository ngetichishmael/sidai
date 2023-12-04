<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reconciliation extends Model
{
    use HasFactory;
    protected $guarded=[''];

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class, 'approved_by', 'id');
   }
   public function warehouse(): BelongsTo
   {
      return $this->belongsTo(warehousing::class, 'warehouse_code', 'warehouse_code');
   }
   public function reconciliationProducts(): hasMany
   {
      return $this->hasMany(ReconciledProducts::class, 'reconciliation_code', 'reconciliation_code');
   }
}

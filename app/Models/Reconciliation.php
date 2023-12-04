<?php

namespace App\Models;

use App\Models\suppliers\suppliers;
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
   public function salesPerson(): BelongsTo
   {
      return $this->belongsTo(User::class, 'sales_person', 'user_code');
   }

   public function warehouse(): BelongsTo
   {
      return $this->belongsTo(warehousing::class, 'warehouse_code', 'warehouse_code');
   }
   public function distributor(): BelongsTo
   {
      return $this->belongsTo(suppliers::class, 'supplierID', 'id');
   }
   public function reconciliationProducts(): hasMany
   {
      return $this->hasMany(ReconciledProducts::class, 'reconciliation_code', 'reconciliation_code');
   }
}

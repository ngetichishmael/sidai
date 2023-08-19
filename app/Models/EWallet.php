<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EWallet extends Model
{
    use HasFactory;
    protected $guarded = [''];
    /**
     * Get the Customer that owns the EWallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Customer(): BelongsTo
    {
        return $this->belongsTo(customers::class, 'customer_id', 'id');
    }
}

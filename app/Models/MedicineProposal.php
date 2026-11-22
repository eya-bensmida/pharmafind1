<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineProposal extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'name',
        'active_ingredient',
        'dosage',
        'form',
        'status',
        'admin_comment',
    ];

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }
}

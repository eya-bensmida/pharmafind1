<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PharmacyMedicament extends Model
{
    protected $table = 'pharmacy_medicament';

    protected $fillable = [
        'pharmacy_id',
        'medicament_id',
        'declared_quantity',
        'updated_stock_at',
    ];

    protected function casts(): array
    {
        return [
            'declared_quantity' => 'integer',
            'updated_stock_at' => 'datetime',
        ];
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function medicament(): BelongsTo
    {
        return $this->belongsTo(Medicament::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicament extends Model
{
    protected $fillable = [
        'name',
        'active_ingredient',
        'dosage',
        'form',
        'category',
        'description',
        'amm',
        'laboratory',
        'veic',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(PharmacyMedicament::class, 'medicament_id');
    }
}

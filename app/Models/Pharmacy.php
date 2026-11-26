<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pharmacy extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'region',
        'type',
        'osm_id',
        'phone',
        'latitude',
        'longitude',
        'osm_id',
        'is_approved',
        'authorization_document_path',
        'order_document_path',
        'verification_status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    // Lien Google Maps : $pharmacy->maps_url
    public function getMapsUrlAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return 'https://www.google.com/maps/search/?api=1&query='
                . $this->latitude . ',' . $this->longitude;
        }

        return 'https://www.google.com/maps/search/?api=1&query='
            . urlencode($this->name . ' ' . $this->address . ' ' . $this->city . ' Tunisie');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(PharmacyMedicament::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicineProposals(): HasMany
    {
        return $this->hasMany(MedicineProposal::class);
    }
}
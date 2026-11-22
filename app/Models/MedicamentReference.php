<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicamentReference extends Model
{
    protected $table = 'medicament_references';

    protected $fillable = [
        'name',
        'dosage',
        'form',
        'presentation',
        'active_ingredient',
        'category',
        'subcategory',
        'laboratory',
        'amm',
        'indications',
        'product_type',
        'veic',
    ];
}

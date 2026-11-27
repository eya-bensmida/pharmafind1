<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PharmacySearch
{
    /**
     * Recherche les pharmacies qui disposent du médicament demandé.
     *
     * @param string      $medicineName Nom du médicament
     * @param string|null $governorate Gouvernorat
     * @param string|null $city        Ville
     *
     * @return array
     */
    public function search(
        string $medicineName,
        ?string $governorate = null,
        ?string $city = null
    ): array {
        $query = DB::table('pharmacy_medicament as pm')
            ->join('medicaments as m', 'm.id', '=', 'pm.medicament_id')
            ->join('pharmacies as p', 'p.id', '=', 'pm.pharmacy_id')
            ->where('m.name', 'LIKE', '%' . trim($medicineName) . '%')
            ->where('m.is_active', true)
            ->where('p.is_approved', true)
            ->where('p.verification_status', 'approved')
            ->where('pm.declared_quantity', '>', 0);

        // Filtrage par gouvernorat
        if ($governorate !== null && trim($governorate) !== '') {
            $query->where('p.region', 'LIKE', trim($governorate));
        }

        // Filtrage par ville
        if ($city !== null && trim($city) !== '') {
            $query->where('p.city', 'LIKE', trim($city));
        }

        $results = $query
            ->select([
                'p.id as pharmacy_id',
                'p.name as pharmacy_name',
                'p.address',
                'p.city',
                'p.region',
                'p.phone',
                'p.latitude',
                'p.longitude',
                'm.id as medicament_id',
                'm.name as medicament_name',
                'pm.declared_quantity',
                'pm.updated_stock_at',
            ])
            ->orderBy('p.name')
            ->get();

        return $results->map(function ($item) {
            return [
                'pharmacy_id' => $item->pharmacy_id,
                'pharmacy_name' => $item->pharmacy_name,
                'address' => $item->address,
                'city' => $item->city,
                'governorate' => $item->region,
                'phone' => $item->phone,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'medicament_id' => $item->medicament_id,
                'medicament_name' => $item->medicament_name,
                'quantity' => $item->declared_quantity,
                'updated_stock_at' => $item->updated_stock_at,
            ];
        })->toArray();
    }
}

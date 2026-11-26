<?php

namespace Database\Seeders;

use App\Models\Medicament;
use App\Models\Pharmacy;
use App\Models\PharmacyMedicament;
use Illuminate\Database\Seeder;

class PharmacyStockDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Médicaments courants (pour que la démo trouve des résultats) + des médicaments au hasard
        $stems = ['dolipran', 'paracetamol', 'efferalgan', 'aspirin', 'amoxicilline',
                  'ibuprof', 'spasfon', 'smecta', 'augmentin', 'ventolin'];

        $courants = Medicament::where('is_active', true)
            ->where(function ($q) use ($stems) {
                foreach ($stems as $s) {
                    $q->orWhere('name', 'like', "%{$s}%");
                }
            })
            ->limit(40)->pluck('id');

        $hasard = Medicament::where('is_active', true)
            ->inRandomOrder()->limit(40)->pluck('id');

        $pool = $courants->merge($hasard)->unique()->values();

        if ($pool->isEmpty()) {
            $this->command->error('Aucun médicament en base : importe d\'abord ton catalogue.');
            return;
        }

        $now = now();
        $rows = [];

        // Uniquement les pharmacies importées (sans compte pharmacien)
        Pharmacy::whereNull('user_id')->pluck('id')->each(function ($id) use ($pool, &$rows, $now) {
            $nb = min($pool->count(), rand(12, 30));

            foreach ($pool->random($nb) as $medicamentId) {
                $rows[] = [
                    'pharmacy_id'       => $id,
                    'medicament_id'     => $medicamentId,
                    'declared_quantity' => rand(1, 40),
                    'updated_stock_at'  => $now,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }
        });

        foreach (array_chunk($rows, 500) as $chunk) {
            PharmacyMedicament::insertOrIgnore($chunk);
        }

        // Environ 15 % des pharmacies importées passent en "nuit" pour la démo
        $total = Pharmacy::whereNull('user_id')->count();
        $ids = Pharmacy::whereNull('user_id')->inRandomOrder()
            ->limit((int) ($total * 0.15))->pluck('id');

        Pharmacy::whereIn('id', $ids)->update(['type' => 'nuit']);

        $this->command->info(count($rows) . ' lignes de stock fictives créées.');
    }
}
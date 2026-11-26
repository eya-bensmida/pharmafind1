<?php

namespace App\Console\Commands;

use App\Models\Pharmacy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class EnrichPharmaciesGeolocation extends Command
{
    protected $signature = 'app:enrich-pharmacies-geolocation
                            {--dry-run : Simuler sans modifier la base}
                            {--limit=10 : Nombre maximum de pharmacies à traiter}';

    protected $description = 'Complète uniquement les villes manquantes des pharmacies via Nominatim';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $limit = max(1, (int) $this->option('limit'));

        // Sélectionner uniquement les pharmacies sans ville.
        $pharmacies = Pharmacy::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereNull('user_id')
            ->whereNotNull('osm_id')
            ->where(function ($query) {
                $query->whereNull('city')
                    ->orWhere('city', '')
                    ->orWhere('city', 'Non renseignée');
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($pharmacies->isEmpty()) {
            $this->info('Aucune pharmacie avec une ville manquante.');
            return self::SUCCESS;
        }

        $this->info(
            'Pharmacies avec ville manquante à examiner : '
            . $pharmacies->count()
        );

        if ($dryRun) {
            $this->warn('MODE SIMULATION : aucune donnée ne sera enregistrée.');
        }

        $updated = 0;
        $failed = 0;
        $notFound = 0;

        foreach ($pharmacies as $pharmacy) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'PharmaFind/1.0 (pharmacy directory project)',
                ])->timeout(20)->get(
                    'https://nominatim.openstreetmap.org/reverse',
                    [
                        'format' => 'jsonv2',
                        'lat' => $pharmacy->latitude,
                        'lon' => $pharmacy->longitude,
                        'addressdetails' => 1,
                        'accept-language' => 'fr',
                    ]
                );

                if (!$response->successful()) {
                    $failed++;
                    $this->warn(
                        "Échec HTTP pour la pharmacie #{$pharmacy->id}"
                    );
                    sleep(1);
                    continue;
                }

                $geo = $response->json('address', []);

                // Priorité aux véritables localités.
                $city = $geo['city']
                    ?? $geo['town']
                    ?? $geo['village']
                    ?? $geo['municipality']
                    ?? null;

                // Solutions de remplacement si aucune ville n'existe.
                $locality = $geo['city_district']
                    ?? $geo['county']
                    ?? $geo['suburb']
                    ?? null;

                $cityToSave = $city ?? $locality;

                if (!$cityToSave) {
                    $notFound++;

                    $this->warn(
                        "Pharmacie #{$pharmacy->id} : ville introuvable."
                    );
                } else {
                    $this->line(
                        "Pharmacie #{$pharmacy->id} : ville trouvée : "
                        . $cityToSave
                    );

                    if (!$dryRun) {
                        $pharmacy->update([
                            'city' => $cityToSave,
                        ]);
                    }

                    $updated++;
                }
            } catch (\Throwable $exception) {
                $failed++;

                $this->warn(
                    "Erreur pharmacie #{$pharmacy->id} : "
                    . $exception->getMessage()
                );
            }

            // Respecter la limite publique de Nominatim.
            sleep(1);
        }

        $this->newLine();
        $this->info("Villes potentiellement complétées : {$updated}");
        $this->info("Villes introuvables : {$notFound}");
        $this->info("Échecs : {$failed}");

        if ($dryRun) {
            $this->warn(
                'Simulation terminée : aucune modification enregistrée.'
            );
        }

        return self::SUCCESS;
    }
}

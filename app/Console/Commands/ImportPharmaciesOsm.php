<?php

namespace App\Console\Commands;

use App\Models\Pharmacy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportPharmaciesOsm extends Command
{
    protected $signature = 'app:import-pharmacies-osm';

    protected $description = 'Importe les pharmacies de toute la Tunisie depuis OpenStreetMap';

    public function handle(): int
    {
        $this->info("Préparation de l'import des pharmacies dans toute la Tunisie...");

        $endpoint = 'https://ovp.dresel.systems/api-af/interpreter';

        try {
            // 1. Vérifier que la zone de la Tunisie existe sur Overpass

            $areaQuery = '
                [out:json][timeout:30];
                area["ISO3166-1"="TN"]["admin_level"="2"];
                out ids;
            ';

            $areaResponse = Http::withHeaders([
                'User-Agent' => 'PharmaFind/1.0 (pharmacy directory import)',
            ])
                ->asForm()
                ->timeout(60)
                ->post($endpoint, [
                    'data' => $areaQuery,
                ]);

            if (!$areaResponse->successful()) {
                $this->error("Erreur lors de la vérification du territoire : HTTP {$areaResponse->status()}");
                return self::FAILURE;
            }

            $areas = $areaResponse->json('elements');

            if (!is_array($areas) || count($areas) === 0) {
                $this->error("La frontière administrative de la Tunisie n'a pas été trouvée.");
                return self::FAILURE;
            }

            $this->info("Territoire de la Tunisie identifié.");

            // 2. Rechercher toutes les pharmacies du pays

            $query = '
                [out:json][timeout:180];

                area["ISO3166-1"="TN"]["admin_level"="2"]->.tunisia;

                (
                    nwr["amenity"="pharmacy"](area.tunisia);
                );

                out center tags;
            ';

            $response = Http::withHeaders([
                'User-Agent' => 'PharmaFind/1.0 (pharmacy directory import)',
            ])
                ->asForm()
                ->timeout(240)
                ->post($endpoint, [
                    'data' => $query,
                ]);

            if (!$response->successful()) {
                $this->error("Erreur Overpass : HTTP {$response->status()}");
                return self::FAILURE;
            }

            $elements = $response->json('elements');

            if (!is_array($elements)) {
                $this->error("La réponse OpenStreetMap est invalide.");
                return self::FAILURE;
            }

            $this->info(count($elements) . ' éléments reçus.');

            if (count($elements) === 0) {
                $this->warn("Aucune pharmacie trouvée dans les données OpenStreetMap.");
                return self::FAILURE;
            }

            // 3. Enregistrer les pharmacies dans MySQL

            $imported = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($elements as $element) {

                $tags = $element['tags'] ?? [];

                $name = trim($tags['name'] ?? '');

                if ($name === '') {
                    $skipped++;
                    continue;
                }

                $osmId = ($element['type'] ?? '') . '/' .
                    ($element['id'] ?? '');

                if ($osmId === '/') {
                    $skipped++;
                    continue;
                }

                $latitude = $element['lat']
                    ?? $element['center']['lat']
                    ?? null;

                $longitude = $element['lon']
                    ?? $element['center']['lon']
                    ?? null;

                $address = $tags['addr:full'] ?? null;

                if (!$address) {
                    $street = $tags['addr:street'] ?? '';
                    $number = $tags['addr:housenumber'] ?? '';

                    $address = trim($number . ' ' . $street);
                }

                if ($address === '') {
                    $address = 'Adresse non renseignée';
                }

                $city = $tags['addr:city']
                    ?? $tags['addr:town']
                    ?? $tags['addr:village']
                    ?? $tags['addr:suburb']
                    ?? 'Non renseignée';

                $region = $tags['addr:state']
                    ?? $tags['addr:province']
                    ?? $tags['is_in:state']
                    ?? $tags['is_in:province']
                    ?? 'Non renseignée';

                $phone = $tags['phone']
                    ?? $tags['contact:phone']
                    ?? null;

                $pharmacy = Pharmacy::where('osm_id', $osmId)->first();

                // Ne pas modifier une pharmacie déjà associée à un compte

                if ($pharmacy && $pharmacy->user_id !== null) {
                    $skipped++;
                    continue;
                }

                $data = [
                    'name' => $name,
                    'address' => $address,
                    'city' => $city,
                    'region' => $region,
                    'phone' => $phone,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ];

                if ($pharmacy) {

                    $pharmacy->update($data);
                    $updated++;

                } else {

                    Pharmacy::create(array_merge($data, [
                        'user_id' => null,
                        'osm_id' => $osmId,
                        'is_approved' => false,
                        'verification_status' => 'pending',
                    ]));

                    $imported++;
                }
            }

            // 4. Afficher le résultat

            $this->newLine();
            $this->info('Import terminé.');

            $this->table(
                ['Résultat', 'Nombre'],
                [
                    ['Nouvelles pharmacies', $imported],
                    ['Fiches actualisées', $updated],
                    ['Éléments ignorés', $skipped],
                ]
            );

            return self::SUCCESS;

        } catch (Throwable $exception) {

            Log::error('Erreur import OpenStreetMap', [
                'message' => $exception->getMessage(),
            ]);

            $this->error(
                'Une erreur est survenue pendant l’import : ' .
                $exception->getMessage()
            );

            return self::FAILURE;
        }
    }
}

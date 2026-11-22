<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MedicamentsImport;

class ImportMedicamentsExcel extends Command
{
    protected $signature = 'pharmafind:import-medicaments';

    protected $description = 'Importer les médicaments Excel et les associer aux pharmacies';

    public function handle()
    {
        $this->info('Debut de l importation des medicaments...');

        // Chemin du fichier Excel
        $file = database_path(
            'imports/pharmafind_medicaments_300_verification.xlsx'
        );

        if (!File::exists($file)) {
            $this->error('Fichier Excel introuvable : ' . $file);

            return self::FAILURE;
        }

        try {

            // 1. Importer les médicaments dans la table centrale
            Excel::import(new MedicamentsImport(), $file);

            $this->info('Importation Excel terminee.');

            // 2. Recuperer les pharmacies
            $pharmacyIds = DB::table('pharmacies')
                ->pluck('id');

            // 3. Recuperer les medicaments actifs
            $medicaments = DB::table('medicaments')
                ->where('is_active', true)
                ->pluck('id');

            if ($pharmacyIds->isEmpty()) {
                $this->warn('Aucune pharmacie trouvee.');

                return self::SUCCESS;
            }

            // 4. Associer les medicaments a chaque pharmacie
            $totalAssociations = 0;

            foreach ($pharmacyIds as $pharmacyId) {

                $now = now();
                $associations = [];

                foreach ($medicaments as $medicamentId) {

                    $associations[] = [
                        'pharmacy_id' => $pharmacyId,
                        'medicament_id' => $medicamentId,
                        'declared_quantity' => 0,
                        'updated_stock_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Ne pas creer de doublons
                DB::table('pharmacy_medicament')
                    ->insertOrIgnore($associations);

                $totalAssociations += count($associations);
            }

            // 5. Afficher le bilan
            $this->newLine();

            $this->info('IMPORTATION TERMINEE AVEC SUCCES');

            $this->table(
                ['Information', 'Resultat'],
                [
                    ['Medicaments dans le catalogue', $medicaments->count()],
                    ['Pharmacies concernees', $pharmacyIds->count()],
                    ['Associations tentees', $totalAssociations],
                ]
            );

            return self::SUCCESS;

        } catch (\Throwable $e) {

            $this->error('Erreur : ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}

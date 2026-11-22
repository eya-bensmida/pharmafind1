<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportMedicamentReferences;
use App\Models\MedicamentReference;

class ImportCatalogueMedicaments extends Command
{
    protected $signature = 'pharmafind:import-catalogue';

    protected $description = 'Importer le catalogue national des médicaments';

    public function handle(): int
    {
        $path = database_path('imports/liste_amm.xls');

        if (!File::exists($path)) {
            $this->error('Fichier liste_amm.xls introuvable.');
            return self::FAILURE;
        }

        $this->info('Début de l importation du catalogue...');

        try {
            $import = new ImportMedicamentReferences();

            Excel::import($import, $path);

            $this->newLine();

            $this->info('Importation terminée !');

            $this->table(
                ['Résultat', 'Nombre'],
                [
                    ['Nouveaux médicaments', $import->inserted],
                    ['Médicaments actualisés', $import->updated],
                    ['Lignes ignorées', $import->skipped],
                    [
                        'Total dans le catalogue',
                        MedicamentReference::count()
                    ],
                ]
            );

            return self::SUCCESS;

        } catch (\Throwable $e) {

            $this->error('Erreur pendant l importation :');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}

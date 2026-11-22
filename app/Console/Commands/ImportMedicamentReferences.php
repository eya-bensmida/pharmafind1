<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportMedicamentReferences extends Command
{
    protected $signature = 'pharmafind:import-references';

    protected $description = 'Importer le catalogue de référence des médicaments tunisiens';

    public function handle()
    {
        $file = database_path('imports/liste_amm.xls');

        if (!File::exists($file)) {
            $this->error("Fichier introuvable : " . $file);
            return self::FAILURE;
        }

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();

            DB::table('medicament_references')->truncate();

            $batch = [];
            $total = 0;

            foreach ($sheet->getRowIterator(2) as $row) {
                $cells = [];

                foreach ($row->getCellIterator() as $cell) {
                    $cells[] = trim((string) $cell->getFormattedValue());
                }

                $name = $cells[0] ?? '';

                if ($name === '') {
                    continue;
                }

                $batch[] = [
                    'name' => $name,
                    'dosage' => $cells[1] ?? null,
                    'form' => $cells[2] ?? null,
                    'presentation' => $cells[3] ?? null,
                    'active_ingredient' => $cells[4] ?? null,
                    'category' => $cells[5] ?? null,
                    'subcategory' => $cells[6] ?? null,
                    'laboratory' => $cells[7] ?? null,
                    'amm' => $cells[8] ?? null,
                    'indications' => $cells[14] ?? null,
                    'product_type' => $cells[15] ?? null,
                    'veic' => $cells[16] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= 500) {
                    DB::table('medicament_references')->insert($batch);
                    $total += count($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                DB::table('medicament_references')->insert($batch);
                $total += count($batch);
            }

            $this->info("Importation terminée : {$total} références.");

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}

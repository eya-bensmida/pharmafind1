<?php

namespace Database\Seeders;

use App\Models\Medicament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MedicamentSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path(
            'imports/pharmafind_medicaments_300.csv'
        );

        if (!file_exists($file)) {
            throw new RuntimeException(
                "Fichier CSV introuvable : " . $file
            );
        }

        $handle = fopen($file, 'r');

        if ($handle === false) {
            throw new RuntimeException(
                "Impossible d'ouvrir le fichier CSV."
            );
        }

        $headers = fgetcsv($handle, 0, ',', '"', '\\');

        if ($headers === false) {
            fclose($handle);
            throw new RuntimeException("Le fichier CSV est vide.");
        }

        $headers = array_map(function ($header) {
            return strtolower(trim(
                preg_replace('/^\xEF\xBB\xBF/', '', $header)
            ));
        }, $headers);

        $required = [
            'name',
            'active_ingredient',
            'dosage',
            'form',
            'category',
            'description',
            'amm',
            'laboratory',
            'veic',
        ];

        foreach ($required as $column) {
            if (!in_array($column, $headers, true)) {
                fclose($handle);

                throw new RuntimeException(
                    "Colonne manquante : " . $column
                );
            }
        }

        $imported = 0;

        DB::transaction(function () use (
            $handle,
            $headers,
            &$imported
        ) {
            while (($row = fgetcsv(
                $handle,
                0,
                ',',
                '"',
                '\\'
            )) !== false) {

                if (count($row) !== count($headers)) {
                    continue;
                }

                $data = array_combine($headers, $row);

                $clean = [];

                foreach ($data as $key => $value) {
                    $clean[$key] = trim((string) $value);
                }

                if ($clean['name'] === '') {
                    continue;
                }

                Medicament::updateOrCreate(
                    [
                        'name' => $clean['name'],
                        'active_ingredient' => $clean['active_ingredient'],
                        'dosage' => $clean['dosage'],
                        'form' => $clean['form'],
                        'laboratory' => $clean['laboratory'],
                    ],
                    [
                        'category' => $clean['category'],
                        'description' => $clean['description'],
                        'amm' => $clean['amm'] !== ''
                            ? $clean['amm']
                            : null,
                        'veic' => $clean['veic'],
                        'is_active' => true,
                    ]
                );

                $imported++;
            }
        });

        fclose($handle);

        $this->command->info(
            $imported . ' lignes de médicaments traitées.'
        );
    }
}

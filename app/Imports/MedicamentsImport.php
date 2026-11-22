<?php

namespace App\Imports;

use App\Models\Medicament;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MedicamentsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Les deux premières lignes ne contiennent pas de médicaments.
        $medicaments = $rows->slice(2);

        foreach ($medicaments as $row) {

            $name = trim((string) ($row[0] ?? ''));
            $amm = trim((string) ($row[6] ?? ''));

            // Ignorer les lignes vides ou sans nom/AMM.
            if ($name === '' || $amm === '') {
                continue;
            }

            Medicament::updateOrCreate(
                ['amm' => $amm],
                [
                    'name' => $name,
                    'active_ingredient' => $row[1] ?? null,
                    'dosage' => $row[2] ?? null,
                    'form' => $row[3] ?? null,
                    'category' => $row[4] ?? null,
                    'description' => $row[5] ?? null,
                    'laboratory' => $row[7] ?? null,
                    'veic' => $row[8] ?? null,
                    'is_active' => true,
                ]
            );
        }
    }
}

<?php

namespace App\Imports;

use App\Models\MedicamentReference;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportMedicamentReferences implements ToCollection, WithStartRow
{
    public int $inserted = 0;
    public int $updated = 0;
    public int $skipped = 0;

    public function startRow(): int
    {
        return 2;
    }

    private function clean($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {

            $name = $this->clean($row[0] ?? null);

            if (!$name) {
                $this->skipped++;
                continue;
            }

            $amm = $this->clean($row[8] ?? null);

            $data = [
                'name' => $name,
                'dosage' => $this->clean($row[1] ?? null),
                'form' => $this->clean($row[2] ?? null),
                'presentation' => $this->clean($row[3] ?? null),
                'active_ingredient' => $this->clean($row[4] ?? null),
                'category' => $this->clean($row[5] ?? null),
                'subcategory' => $this->clean($row[6] ?? null),
                'laboratory' => $this->clean($row[7] ?? null),
                'amm' => $amm,
                'indications' => $this->clean($row[14] ?? null),
                'product_type' => $this->clean($row[15] ?? null),
                'veic' => $this->clean($row[16] ?? null),
            ];

            // Recherche par numéro AMM.
            if ($amm !== null) {
                $reference = MedicamentReference::where('amm', $amm)
                    ->first();
            } else {
                // Si l'AMM est absente, recherche par identité du médicament.
                $reference = MedicamentReference::where('name', $name)
                    ->where('dosage', $data['dosage'])
                    ->where('form', $data['form'])
                    ->where('laboratory', $data['laboratory'])
                    ->first();
            }

            if ($reference) {
                $reference->update($data);
                $this->updated++;
            } else {
                MedicamentReference::create($data);
                $this->inserted++;
            }
        }
    }
}

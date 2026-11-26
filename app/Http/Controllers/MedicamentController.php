<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use App\Models\MedicamentReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicamentController extends Controller
{
    private function getPharmacy()
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy || !$pharmacy->is_approved) {
            abort(403, "Votre pharmacie n'est pas encore approuvée.");
        }

        return $pharmacy;
    }

    /**
     * Normalisation des textes.
     */
    private function normalize(?string $value): string
    {
        return Str::lower(
            Str::ascii(trim($value ?? ''))
        );
    }

    /**
     * Calcul de ressemblance entre deux textes.
     */
    private function similarity(string $a, string $b): float
    {
        $a = $this->normalize($a);
        $b = $this->normalize($b);

        if ($a === '' || $b === '') {
            return 0;
        }

        if ($a === $b) {
            return 100;
        }

        if (str_contains($b, $a)) {
            return 95;
        }

        similar_text($a, $b, $percentage);

        // Comparaison également avec chaque mot.
        foreach (preg_split('/\s+/', $b) as $word) {
            similar_text($a, $word, $wordPercentage);
            $percentage = max($percentage, $wordPercentage);
        }

        return $percentage;
    }

    /**
     * Recherche intelligente dans le catalogue officiel.
     */
    public function searchSuggestions(Request $request)
    {
        $this->getPharmacy();

        $term = trim($request->input('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $references = MedicamentReference::query()
            ->get([
                'id',
                'name',
                'active_ingredient',
                'dosage',
                'form',
                'presentation',
                'category',
                'laboratory',
                'amm',
                'indications',
                'product_type',
                'veic',
            ]);

        $results = [];

        foreach ($references as $reference) {

            $fields = [
                $reference->name,
                $reference->active_ingredient,
                $reference->category,
                $reference->subcategory,
                $reference->laboratory,
            ];

            $bestScore = 0;

            foreach ($fields as $field) {

                $score = $this->similarity(
                    $term,
                    (string) $field
                );

                $bestScore = max($bestScore, $score);
            }

            if ($bestScore >= 45) {

                $results[] = [
                    'id' => $reference->id,
                    'name' => $reference->name,
                    'active_ingredient' => $reference->active_ingredient,
                    'dosage' => $reference->dosage,
                    'form' => $reference->form,
                    'presentation' => $reference->presentation,
                    'category' => $reference->category,
                    'laboratory' => $reference->laboratory,
                    'amm' => $reference->amm,
                    'indications' => $reference->indications,
                    'product_type' => $reference->product_type,
                    'veic' => $reference->veic,
                    'score' => round($bestScore),
                ];
            }
        }

        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return response()->json(array_slice($results, 0, 10));
    }

    /**
     * Liste des médicaments du stock de la pharmacie.
     * Recherche approximative et affichage sans pagination.
     */
    public function index(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $search = trim($request->input('q', ''));

        $medicaments = Medicament::whereHas('stocks', function ($query) use ($pharmacy) {
            $query->where('pharmacy_id', $pharmacy->id);
        })
        ->with(['stocks' => function ($query) use ($pharmacy) {
            $query->where('pharmacy_id', $pharmacy->id);
        }])
        ->get();

        if ($search !== '') {

            $medicaments = $medicaments->filter(function ($medicament) use ($search) {

                $fields = [
                    $medicament->name,
                    $medicament->active_ingredient,
                    $medicament->category,
                    $medicament->laboratory,
                ];

                foreach ($fields as $field) {

                    if ($this->similarity(
                        $search,
                        (string) $field
                    ) >= 45) {
                        return true;
                    }
                }

                return false;
            });
        }

        $medicaments = $medicaments
            ->sortBy('name')
            ->values();

        $total = $medicaments->count();

        return view('medicaments.index', compact(
            'medicaments',
            'search',
            'total'
        ));
    }

    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        $this->getPharmacy();

        return view('medicaments.create');
    }

    /**
     * Enregistrement à partir du catalogue de référence.
     */
    public function store(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $validated = $request->validate([
            'reference_id' => 'required|exists:medicament_references,id',
            'declared_quantity' => 'required|integer|min:0',
        ], [
            'reference_id.required' => 'Veuillez sélectionner un médicament du catalogue.',
            'reference_id.exists' => 'Ce médicament est introuvable dans le catalogue.',
            'declared_quantity.required' => 'Veuillez saisir la quantité.',
            'declared_quantity.integer' => 'La quantité doit être un nombre entier.',
            'declared_quantity.min' => 'La quantité ne peut pas être négative.',
        ]);

        $reference = MedicamentReference::findOrFail(
            $validated['reference_id']
        );

        $result = DB::transaction(function () use (
            $reference,
            $pharmacy,
            $validated
        ) {

            /*
             * 1. Rechercher le médicament dans le catalogue central.
             * L'AMM est l'identifiant prioritaire lorsqu'elle existe.
             */
            $medicament = null;

            if (!empty($reference->amm)) {

                $medicament = Medicament::where(
                    'amm',
                    $reference->amm
                )->first();
            }

            /*
             * 2. Si aucune correspondance AMM, chercher par
             * nom, dosage et forme.
             */
            if (!$medicament) {

                $medicament = Medicament::whereRaw(
                    'LOWER(TRIM(name)) = ?',
                    [Str::lower(trim($reference->name))]
                )
                ->where(function ($query) use ($reference) {

                    if ($reference->dosage !== null) {
                        $query->where('dosage', $reference->dosage);
                    } else {
                        $query->whereNull('dosage');
                    }

                })
                ->where(function ($query) use ($reference) {

                    if ($reference->form !== null) {
                        $query->where('form', $reference->form);
                    } else {
                        $query->whereNull('form');
                    }

                })
                ->first();
            }

            /*
             * 3. Si le médicament n'existe pas dans la table
             * centrale, le créer à partir de la référence.
             */
            if (!$medicament) {

                $medicament = Medicament::create([
                    'name' => $reference->name,
                    'active_ingredient' => $reference->active_ingredient,
                    'dosage' => $reference->dosage,
                    'form' => $reference->form,
                    'category' => $reference->category,
                    'description' => $reference->indications,
                    'amm' => $reference->amm,
                    'laboratory' => $reference->laboratory,
                    'veic' => $reference->veic,
                    'is_active' => true,
                ]);
            }

            /*
             * 4. Vérifier si la pharmacie possède déjà
             * ce médicament dans son stock.
             */
            $existingStock = $pharmacy->stocks()
                ->where('medicament_id', $medicament->id)
                ->lockForUpdate()
                ->first();

            if ($existingStock) {
                return 'duplicate';
            }

            /*
             * 5. Créer uniquement l'association avec la pharmacie.
             * Le catalogue central n'est pas recréé.
             */
            $pharmacy->stocks()->create([
                'medicament_id' => $medicament->id,
                'declared_quantity' => $validated['declared_quantity'],
                'updated_stock_at' => now(),
            ]);

            return 'success';
        });

        if ($result === 'duplicate') {

            return back()
                ->withInput()
                ->with(
                    'duplicate',
                    'Ce médicament existe déjà dans votre stock. Aucun doublon n’a été créé.'
                );
        }

        return redirect()
            ->route('pharmacien.medicaments.index')
            ->with(
                'success',
                'Médicament ajouté avec succès à votre stock.'
            );
    }

    /**
     * Modification du stock.
     */
    public function edit(Medicament $medicament)
    {
        $pharmacy = $this->getPharmacy();

        $stock = $pharmacy->stocks()
            ->where('medicament_id', $medicament->id)
            ->firstOrFail();

        return view('medicaments.edit', compact(
            'medicament',
            'stock'
        ));
    }

    public function update(Request $request, Medicament $medicament)
    {
        $pharmacy = $this->getPharmacy();

        $validated = $request->validate([
            'declared_quantity' => 'required|integer|min:0',
        ]);

        $stock = $pharmacy->stocks()
            ->where('medicament_id', $medicament->id)
            ->firstOrFail();

        $stock->update([
            'declared_quantity' => $validated['declared_quantity'],
            'updated_stock_at' => now(),
        ]);
        
                if ($request->expectsJson()) {
            return response()->json([
                'quantity'   => $stock->declared_quantity,
                'updated_at' => $stock->updated_stock_at->locale('fr')->diffForHumans(),
            ]);
        }

        return redirect()
            ->route('pharmacien.medicaments.index')
            ->with('success', 'Stock modifié avec succès.');
    }

    /**
     * Retirer un médicament du stock de la pharmacie.
     */
    public function destroy(Medicament $medicament)
    {
        $pharmacy = $this->getPharmacy();

        $stock = $pharmacy->stocks()
            ->where('medicament_id', $medicament->id)
            ->firstOrFail();

        $stock->delete();

        return redirect()
            ->route('pharmacien.medicaments.index')
            ->with('success', 'Médicament retiré de votre stock.');
    }
}

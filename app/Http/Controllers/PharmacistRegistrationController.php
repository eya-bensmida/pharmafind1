<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PharmacistRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.pharmacist-register');
    }

    /**
     * Suggestions automatiques des pharmacies existantes
     * qui ne sont pas encore associées à un compte.
     */
    public function searchPharmacies(Request $request)
    {
        $term = trim($request->input('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $pharmacies = Pharmacy::query()
            ->whereNull('user_id')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orderBy('name')
            ->limit(10)
            ->get([
                'id',
                'name',
                'address',
                'city',
                'region',
                'phone',
                'latitude',
                'longitude',
                'type',
            ]);

        return response()->json($pharmacies);
    }

    /**
     * Création d'un compte responsable et association
     * à une pharmacie déjà présente dans le répertoire.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone' => ['required', 'string', 'max:20'],

            'pharmacy_id' => ['required', 'integer', 'exists:pharmacies,id'],

            'pharmacy_phone' => ['nullable', 'string', 'max:20'],

            'type' => ['required', 'in:jour,nuit'],

            'authorization_document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'order_document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $authorizationPath = null;
        $orderPath = null;

        try {
            $authorizationPath = $request
                ->file('authorization_document')
                ->store('pharmacy-documents', 'local');

            $orderPath = $request
                ->file('order_document')
                ->store('pharmacy-documents', 'local');

            DB::transaction(function () use (
                $validated,
                $authorizationPath,
                $orderPath
            ) {
                // Verrouillage de la fiche pour empêcher
                // deux responsables de la réclamer simultanément.
                $pharmacy = Pharmacy::whereKey($validated['pharmacy_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$pharmacy || $pharmacy->user_id !== null) {
                    throw ValidationException::withMessages([
                        'pharmacy_id' =>
                            'Cette pharmacie est déjà associée à un compte ou n’est plus disponible.',
                    ]);
                }

                $user = User::create([
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'phone' => $validated['phone'],
                    'role' => 'owner',
                    'account_status' => 'pending',
                ]);

                // On conserve les données OSM existantes.
                // Le téléphone importé n'est remplacé que s'il est absent.
                $pharmacy->update([
                    'user_id' => $user->id,
                    'type' => $validated['type'],
                    'phone' => $pharmacy->phone
                        ?: ($validated['pharmacy_phone'] ?? null),
                    'is_approved' => false,
                    'verification_status' => 'pending',
                    'rejection_reason' => null,
                    'authorization_document_path' => $authorizationPath,
                    'order_document_path' => $orderPath,
                ]);
            });

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Votre demande d’association a été envoyée avec succès. '
                    . 'Elle sera examinée par notre administrateur.'
                );

        } catch (\Throwable $e) {

            if ($authorizationPath) {
                Storage::disk('local')->delete($authorizationPath);
            }

            if ($orderPath) {
                Storage::disk('local')->delete($orderPath);
            }

            throw $e;
        }
    }
}

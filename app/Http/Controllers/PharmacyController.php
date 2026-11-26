<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PharmacyController extends Controller
{
    /**
     * Liste de toutes les pharmacies approuvées.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pharmacies = Pharmacy::with('user')
            ->where('verification_status', 'approved')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.pharmacies', compact(
            'pharmacies',
            'search'
        ));
    }

    /**
     * Liste des demandes d'association en attente.
     */
    public function requests()
    {
        $pharmacies = Pharmacy::with('user')
            ->where('verification_status', 'pending')
            ->whereNotNull('user_id')
            ->latest()
            ->paginate(10);

        return view('admin.pharmacy-requests', compact('pharmacies'));
    }

    /**
     * Afficher les détails d'une demande.
     */
    public function showRequest(Pharmacy $pharmacy)
    {
        abort_unless(
            $pharmacy->verification_status === 'pending'
            && $pharmacy->user_id !== null,
            404
        );

        $pharmacy->load('user');

        abort_unless($pharmacy->user, 404);

        return view('admin.pharmacy-request-details', compact('pharmacy'));
    }

    /**
     * Consulter un justificatif privé.
     */
    public function document(Pharmacy $pharmacy, string $type)
    {
        abort_unless(
            $pharmacy->verification_status === 'pending'
            && $pharmacy->user_id !== null,
            404
        );

        $path = match ($type) {
            'authorization' => $pharmacy->authorization_document_path,
            'order' => $pharmacy->order_document_path,
            default => abort(404),
        };

        abort_unless(
            $path && Storage::disk('local')->exists($path),
            404
        );

        return Storage::disk('local')->response($path);
    }

    /**
     * Approuver une demande d'association.
     */
    public function approve(Pharmacy $pharmacy)
    {
        if (
            $pharmacy->verification_status !== 'pending'
            || $pharmacy->user_id === null
        ) {
            return redirect()
                ->route('admin.pharmacies.requests')
                ->with('error', 'Cette demande a déjà été traitée ou est invalide.');
        }

        $user = DB::transaction(function () use ($pharmacy) {

            $lockedPharmacy = Pharmacy::whereKey($pharmacy->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedPharmacy->verification_status !== 'pending'
                || $lockedPharmacy->user_id === null
            ) {
                return null;
            }

            $user = $lockedPharmacy->user;

            if (!$user) {
                throw new RuntimeException(
                    'Aucun compte utilisateur associé à cette pharmacie.'
                );
            }

            $lockedPharmacy->update([
                'verification_status' => 'approved',
                'is_approved' => true,
                'rejection_reason' => null,
            ]);

            $user->update([
                'account_status' => 'active',
            ]);

            return $user;
        });

        if (!$user) {
            return redirect()
                ->route('admin.pharmacies.requests')
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        $emailSent = true;

        try {
            Mail::raw(
                "Bonjour {$user->name},\n\n"
                . "Votre demande d'association à la pharmacie "
                . "{$pharmacy->name} a été approuvée.\n\n"
                . "Votre compte PharmaFind est maintenant activé.\n"
                . "Vous pouvez vous connecter avec votre adresse e-mail "
                . "et votre mot de passe.\n\n"
                . "Cordialement,\nL'équipe PharmaFind",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Votre compte PharmaFind est activé');
                }
            );
        } catch (\Throwable $e) {
            $emailSent = false;
            report($e);
        }

        $message = 'Demande approuvée avec succès. Le compte est activé.';

        if (!$emailSent) {
            $message .= " Attention : l'envoi de l'e-mail a échoué.";
        }

        return redirect()
            ->route('admin.pharmacies.requests')
            ->with('success', $message);
    }

    /**
     * Refuser une demande et libérer la pharmacie.
     */
    public function reject(Request $request, Pharmacy $pharmacy)
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if (
            $pharmacy->verification_status !== 'pending'
            || $pharmacy->user_id === null
        ) {
            return redirect()
                ->route('admin.pharmacies.requests')
                ->with('error', 'Cette demande a déjà été traitée ou est invalide.');
        }

        $documents = DB::transaction(function () use ($pharmacy, $validated) {

            $lockedPharmacy = Pharmacy::whereKey($pharmacy->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedPharmacy->verification_status !== 'pending'
                || $lockedPharmacy->user_id === null
            ) {
                return null;
            }

            $user = $lockedPharmacy->user;

            if (!$user) {
                throw new RuntimeException(
                    'Aucun compte utilisateur associé à cette pharmacie.'
                );
            }

            $documents = [
                $lockedPharmacy->authorization_document_path,
                $lockedPharmacy->order_document_path,
            ];

            $user->update([
                'account_status' => 'rejected',
            ]);

            $lockedPharmacy->update([
                'user_id' => null,
                'type' => null,
                'verification_status' => 'pending',
                'is_approved' => false,
                'rejection_reason' => $validated['rejection_reason'] ?? null,
                'authorization_document_path' => null,
                'order_document_path' => null,
            ]);

            return $documents;
        });

        if ($documents === null) {
            return redirect()
                ->route('admin.pharmacies.requests')
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        foreach ($documents as $document) {
            if ($document) {
                Storage::disk('local')->delete($document);
            }
        }

        return redirect()
            ->route('admin.pharmacies.requests')
            ->with(
                'success',
                'Demande refusée. La pharmacie est de nouveau disponible pour une association.'
            );
    }
}

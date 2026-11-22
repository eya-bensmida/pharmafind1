<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PharmacyController extends Controller
{
    // Liste des pharmacies approuvées
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
            ->paginate(10)
            ->withQueryString();

        return view('admin.pharmacies', compact(
            'pharmacies',
            'search'
        ));
    }

    // Liste des demandes en attente
    public function requests()
    {
        $pharmacies = Pharmacy::with('user')
            ->where('verification_status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.pharmacy-requests', compact('pharmacies'));
    }

    // Détails d'une demande
    public function showRequest(Pharmacy $pharmacy)
    {
        $pharmacy->load('user');

        return view('admin.pharmacy-request-details', compact('pharmacy'));
    }

    // Consulter un justificatif privé
    public function document(Pharmacy $pharmacy, string $type)
    {
        $path = match ($type) {
            'authorization' => $pharmacy->authorization_document_path,
            'order' => $pharmacy->order_document_path,
            default => abort(404),
        };

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }

    // Approuver une demande
    public function approve(Pharmacy $pharmacy)
    {
        if ($pharmacy->verification_status !== 'pending') {
            return redirect()
                ->route('admin.pharmacies.requests')
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        DB::transaction(function () use ($pharmacy) {

            $pharmacy->update([
                'verification_status' => 'approved',
                'is_approved' => true,
                'rejection_reason' => null,
            ]);

            $pharmacy->user()->update([
                'account_status' => 'active',
                'status' => 'active',
            ]);
        });

        $emailSent = true;

        try {
            Mail::raw(
                "Bonjour {$pharmacy->user->full_name},\n\n"
                ."Votre demande de création d'espace pharmacie "
                ."pour {$pharmacy->name} a été approuvée.\n\n"
                ."Votre compte PharmaFind est maintenant activé.\n"
                ."Vous pouvez vous connecter avec votre adresse e-mail "
                ."et votre mot de passe.\n\n"
                ."Cordialement,\nL'équipe PharmaFind",
                function ($message) use ($pharmacy) {
                    $message->to($pharmacy->user->email)
                        ->subject('Votre compte PharmaFind est activé');
                }
            );
        } catch (\Throwable $e) {
            $emailSent = false;
            report($e);
        }

        $message = 'Demande approuvée avec succès. Le compte est activé.';

        if (!$emailSent) {
            $message .= ' Attention : l’envoi de l’e-mail a échoué.';
        }

        return redirect()
            ->route('admin.pharmacies.requests')
            ->with('success', $message);
    }

    // Refuser une demande
    public function reject(Request $request, Pharmacy $pharmacy)
    {
        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($pharmacy->verification_status !== 'pending') {
            return redirect()
                ->route('admin.pharmacies.requests')
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        DB::transaction(function () use ($pharmacy, $request) {

            $pharmacy->update([
                'verification_status' => 'rejected',
                'is_approved' => false,
                'rejection_reason' => $request->input('rejection_reason'),
            ]);

            $pharmacy->user()->update([
                'account_status' => 'rejected',
                'status' => 'inactive',
            ]);
        });

        return redirect()
            ->route('admin.pharmacies.requests')
            ->with('error', 'Demande refusée.');
    }
}

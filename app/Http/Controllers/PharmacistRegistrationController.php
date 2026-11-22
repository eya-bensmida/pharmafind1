<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PharmacistRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.pharmacist-register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone' => ['required', 'string', 'max:20'],

            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'pharmacy_phone' => ['required', 'string', 'max:20'],

            'authorization_document' => [
                'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'
            ],

            'order_document' => [
                'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'
            ],
        ]);

        $authorizationPath = null;
        $orderPath = null;

        try {
            $authorizationPath = $request->file('authorization_document')
                ->store('pharmacy-documents', 'local');

            $orderPath = $request->file('order_document')
                ->store('pharmacy-documents', 'local');

            DB::transaction(function () use (
                $validated,
                $authorizationPath,
                $orderPath
            ) {
                $user = User::create([
                    'name' => $validated['full_name'],
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'phone' => $validated['phone'],
                    'role' => 'owner',
                    'account_status' => 'pending',
                    'status' => 'inactive',
                ]);

                Pharmacy::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'region' => $validated['region'] ?? null,
                    'phone' => $validated['pharmacy_phone'],
                    'is_approved' => false,
                    'verification_status' => 'pending',
                    'authorization_document_path' => $authorizationPath,
                    'order_document_path' => $orderPath,
                ]);
            });

            return redirect()
                ->route('login')
                ->with('success',
                    'Votre demande a été envoyée avec succès. '
                    .'Elle sera examinée par notre administrateur. '
                    .'Vous recevrez un e-mail après la décision.'
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

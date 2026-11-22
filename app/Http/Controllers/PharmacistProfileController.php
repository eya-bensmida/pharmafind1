<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PharmacistProfileController extends Controller
{
    public function edit()
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy) {
            abort(404, 'Aucune pharmacie associée à ce compte.');
        }

        return view('pharmacien.profile', compact('pharmacy'));
    }

    public function update(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        if (!$pharmacy) {
            abort(404, 'Aucune pharmacie associée à ce compte.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $pharmacy->update($validated);

        return redirect()
            ->route('pharmacien.profile.edit')
            ->with('success', 'Votre profil a été modifié avec succès.');
    }
}

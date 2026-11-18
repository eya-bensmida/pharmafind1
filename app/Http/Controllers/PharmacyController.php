<?php
namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pharmacies = Pharmacy::query()
            ->with('user')
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

        return view('admin.pharmacies', compact('pharmacies', 'search'));
    }

    public function approve(Pharmacy $pharmacy)
    {
        $pharmacy->update(['is_approved' => true]);

        return redirect()
            ->route('admin.pharmacies.index')
            ->with('success', 'La pharmacie a été approuvée.');
    }

    public function reject(Pharmacy $pharmacy)
    {
        $pharmacy->update(['is_approved' => false]);

        return redirect()
            ->route('admin.pharmacies.index')
            ->with('success', 'La pharmacie a été désactivée.');
    }
}
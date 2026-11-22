<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Medicament;
use App\Models\MedicineProposal;
use App\Models\FailedSearch;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingRequests = Pharmacy::where(
            'verification_status',
            'pending'
        )->count();

        $usersCount = User::count();

        $medicamentsCount = Medicament::count();

        $proposalsCount = MedicineProposal::count();

        $failedSearchesCount = FailedSearch::count();

        $approvedPharmacies = Pharmacy::where(
            'verification_status',
            'approved'
        )->count();

        return view('admin.dashboard', compact(
            'pendingRequests',
            'usersCount',
            'medicamentsCount',
            'proposalsCount',
            'failedSearchesCount',
            'approvedPharmacies'
        ));
    }
}

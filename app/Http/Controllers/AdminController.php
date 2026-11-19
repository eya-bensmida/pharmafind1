<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function failedSearches()
    {
        $query = DB::table('failed_searches')
            ->leftJoin('users', 'failed_searches.user_id', '=', 'users.id')
            ->select(
                'failed_searches.*',
                'users.name as user_name',
                'users.email as user_email'
            );

        if (request()->filled('q')) {
            $query->where(
                'failed_searches.search_term',
                'like',
                '%' . request('q') . '%'
            );
        }

        $failedSearches = $query
            ->orderByDesc('failed_searches.last_searched_at')
            ->paginate(15)
            ->withQueryString();

        $totalSearches = DB::table('failed_searches')->count();

        $totalAttempts = DB::table('failed_searches')
            ->sum('search_count');

        return view('admin.failed-searches', compact(
            'failedSearches',
            'totalSearches',
            'totalAttempts'
        ));
    }

public function users()
{
    $query = DB::table('users')
        ->select(
            'id',
            'name',
            'email',
            'phone',
            'role',
            'account_status',
            'created_at'
        );

    if (request()->filled('q')) {
        $search = request('q');

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    if (request()->filled('role')) {
        $query->where('role', request('role'));
    }

    if (request()->filled('status')) {
        $query->where('account_status', request('status'));
    }

    $users = $query
        ->orderByDesc('created_at')
        ->paginate(15)
        ->withQueryString();

    $totalUsers = DB::table('users')->count();

    $activeUsers = DB::table('users')
        ->where('account_status', 'active')
        ->count();

    $pendingUsers = DB::table('users')
        ->where('account_status', 'pending')
        ->count();

    $suspendedUsers = DB::table('users')
        ->where('account_status', 'suspended')
        ->count();

    return view('admin.users', compact(
        'users',
        'totalUsers',
        'activeUsers',
        'pendingUsers',
        'suspendedUsers'
    ));
}

}

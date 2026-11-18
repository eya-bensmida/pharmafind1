<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@pharmafind.tn'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'account_status' => 'active',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
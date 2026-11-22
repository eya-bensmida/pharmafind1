<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->enum('verification_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->text('rejection_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn([
                'verification_status',
                'rejection_reason'
            ]);
        });
    }
};

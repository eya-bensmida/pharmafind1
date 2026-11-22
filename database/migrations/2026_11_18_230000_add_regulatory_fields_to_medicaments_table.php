
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicaments', function (Blueprint $table) {
            $table->string('amm')->nullable();
            $table->string('laboratory')->nullable();
            $table->string('veic')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medicaments', function (Blueprint $table) {
            $table->dropColumn([
                'amm',
                'laboratory',
                'veic',
            ]);
        });
    }
};

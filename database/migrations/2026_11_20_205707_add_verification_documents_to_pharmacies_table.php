
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->string('authorization_document_path')->nullable();
            $table->string('order_document_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn([
                'authorization_document_path',
                'order_document_path',
            ]);
        });
    }
};

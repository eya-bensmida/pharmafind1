
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_medicament', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pharmacy_id')
                ->constrained('pharmacies')
                ->cascadeOnDelete();

            $table->foreignId('medicament_id')
                ->constrained('medicaments')
                ->cascadeOnDelete();

            $table->unsignedInteger('declared_quantity')->default(0);
            $table->timestamp('updated_stock_at')->nullable();
            $table->timestamps();

            $table->unique(['pharmacy_id', 'medicament_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_medicament');
    }
};

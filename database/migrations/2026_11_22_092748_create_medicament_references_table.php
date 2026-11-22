
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicament_references', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dosage')->nullable();
            $table->string('form')->nullable();
            $table->string('presentation')->nullable();
            $table->string('active_ingredient')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('laboratory')->nullable();
            $table->string('amm')->nullable()->index();
            $table->text('indications')->nullable();
            $table->string('product_type')->nullable();
            $table->string('veic')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('active_ingredient');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicament_references');
    }
};

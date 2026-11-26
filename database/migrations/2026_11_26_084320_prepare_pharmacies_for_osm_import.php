
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer temporairement les contraintes existantes.
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropForeign('pharmacies_user_id_foreign');
            $table->dropUnique('pharmacies_user_id_unique');
        });

        // Autoriser les pharmacies sans compte pharmacien.
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->change();
        });

        // Restaurer les contraintes et ajouter l'identifiant OSM.
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->unique('user_id', 'pharmacies_user_id_unique');

            $table->foreign('user_id', 'pharmacies_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->string('osm_id', 100)->nullable()->unique();
        });
    }

    public function down(): void
    {
        // Ne pas supprimer les pharmacies qui n'ont pas encore de compte.
        $nullUsers = DB::table('pharmacies')
            ->whereNull('user_id')
            ->count();

        if ($nullUsers > 0) {
            throw new RuntimeException(
                'Impossible de revenir en arrière : certaines pharmacies n’ont pas encore de compte utilisateur.'
            );
        }

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropUnique(['osm_id']);
            $table->dropColumn('osm_id');

            $table->dropForeign('pharmacies_user_id_foreign');
            $table->dropUnique('pharmacies_user_id_unique');
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                ->nullable(false)
                ->change();

            $table->unique('user_id', 'pharmacies_user_id_unique');

            $table->foreign('user_id', 'pharmacies_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};

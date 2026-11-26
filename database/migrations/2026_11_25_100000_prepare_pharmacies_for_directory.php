
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajouter le type de pharmacie si absent.
        // NULL pour les pharmacies importées dont le type n'est pas vérifié.
        if (!Schema::hasColumn('pharmacies', 'type')) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->enum('type', ['jour', 'nuit'])
                    ->nullable()
                    ->after('region');
            });
        }

        // 2. Ajouter l'index sur region s'il est absent.
        $indexExiste = collect(Schema::getIndexes('pharmacies'))
            ->contains(fn ($index) =>
                $index['columns'] === ['region']
            );

        if (!$indexExiste) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->index('region');
            });
        }

        // 3. user_id est déjà nullable et sa clé étrangère existe.
        // 4. osm_id et son index unique existent déjà.
        // Aucune modification nécessaire pour ces colonnes.
    }

    public function down(): void
    {
        // Ne pas supprimer les colonnes existantes :
        // elles peuvent être utilisées par d'autres fonctionnalités.
        // Aucun rollback destructif.
    }
};

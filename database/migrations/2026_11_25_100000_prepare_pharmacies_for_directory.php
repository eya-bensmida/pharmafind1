<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) supprimer l'ancienne clé étrangère, seulement si elle existe encore
        $fkExiste = collect(Schema::getForeignKeys('pharmacies'))
            ->contains(fn ($fk) => $fk['columns'] === ['user_id']);

        if ($fkExiste) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        // 2) user_id devient optionnel (pharmacies importées sans compte)
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // 3) remettre la clé étrangère (non bloquant : si MySQL la refuse, on continue sans)
        try {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        } catch (\Throwable $e) {
            // la clé étrangère n'est pas indispensable au fonctionnement
        }

        // 4) nouvelles colonnes
        if (! Schema::hasColumn('pharmacies', 'type')) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->enum('type', ['jour', 'nuit'])->default('jour')->after('region');
            });
        }

        if (! Schema::hasColumn('pharmacies', 'osm_id')) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->string('osm_id')->nullable()->unique()->after('type');
            });
        }

        if (! Schema::hasIndex('pharmacies', ['region'])) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->index('region');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn(['type', 'osm_id']);
        });
    }
};
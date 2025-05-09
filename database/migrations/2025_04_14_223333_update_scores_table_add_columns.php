<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            // Ajout des colonnes manquantes avec contraintes
            $table->unsignedInteger('correct_answers')
                  ->after('score')
                  ->default(0)
                  ->comment('Nombre de réponses correctes');

            $table->unsignedInteger('total_questions')
                  ->after('correct_answers')
                  ->default(10)
                  ->comment('Nombre total de questions');

            $table->unsignedInteger('time_seconds')
                  ->after('total_questions')
                  ->nullable()
                  ->comment('Temps de réponse en secondes (nullable)');
        });

        // Mise à jour des données existantes
        if (Schema::hasTable('scores')) {
            DB::table('scores')->update([
                'correct_answers' => DB::raw('FLOOR(score/10)'), // Convertit le % en nombre de bonnes réponses
                'total_questions' => 10,
                'time_seconds' => DB::raw('COALESCE(time_seconds, 0)')
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            // Suppression sécurisée des colonnes
            if (Schema::hasColumn('scores', 'correct_answers')) {
                $table->dropColumn('correct_answers');
            }
            if (Schema::hasColumn('scores', 'total_questions')) {
                $table->dropColumn('total_questions');
            }
            if (Schema::hasColumn('scores', 'time_seconds')) {
                $table->dropColumn('time_seconds');
            }
        }); 
    }
};
// database/migrations/2025_04_15_000000_create_scores_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Crée la table si elle n'existe pas
        if (!Schema::hasTable('scores')) {
            Schema::create('scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->decimal('score', 5, 2);
                
                // Vos colonnes originales
                $table->integer('correct_answers')->after('score');
                $table->integer('total_questions')->after('correct_answers');
                $table->integer('time_seconds')->nullable()->after('total_questions');
                
                $table->timestamps();
            });
        }
        // Sinon, ajoute juste les colonnes manquantes
        else {
            Schema::table('scores', function (Blueprint $table) {
                if (!Schema::hasColumn('scores', 'correct_answers')) {
                    $table->integer('correct_answers')->after('score');
                }
                if (!Schema::hasColumn('scores', 'total_questions')) {
                    $table->integer('total_questions')->after('correct_answers');
                }
                if (!Schema::hasColumn('scores', 'time_seconds')) {
                    $table->integer('time_seconds')->nullable()->after('total_questions');
                }
            });
        }
    }

    public function down()
    {
        // Laisser vide pour protéger les données existantes
    }
};
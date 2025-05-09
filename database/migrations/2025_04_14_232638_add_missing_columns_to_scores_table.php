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
        $table->integer('correct_answers')->after('score');
        $table->integer('total_questions')->after('correct_answers');
        $table->integer('time_seconds')->nullable()->after('total_questions');
    });

    // Optionnel : valeurs par défaut pour les données existantes
    DB::table('scores')->update([
        'correct_answers' => DB::raw('score'), // Adaptez selon votre logique
        'total_questions' => 10,               // Valeur par défaut
        'time_seconds' => 0                    // Valeur par défaut
    ]);
}

public function down()
{
    Schema::table('scores', function (Blueprint $table) {
        $table->dropColumn(['correct_answers', 'total_questions', 'time_seconds']);
    });
}
};

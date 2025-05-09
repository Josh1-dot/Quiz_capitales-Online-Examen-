<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();  // Colonne id (bigint)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();  // user_id (bigint)
            $table->decimal('score', 5, 2);  // score (decimal)
            $table->unsignedInteger('correct_answers')->default(0);  // correct_answers (int)
            $table->unsignedInteger('total_questions')->default(10);  // total_questions (int)
            $table->timestamps();  // created_at + updated_at (timestamp)
            
            // Force le moteur et l'encodage
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
};

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
        if (!Schema::hasTable('scores')) {
            Schema::create('scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->decimal('score', 5, 2);
                $table->unsignedInteger('correct_answers')->default(0);
                $table->unsignedInteger('total_questions')->default(10);
                $table->unsignedInteger('time_seconds')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('scores', function (Blueprint $table) {
                if (!Schema::hasColumn('scores', 'correct_answers')) {
                    $table->unsignedInteger('correct_answers')->default(0);
                }
                if (!Schema::hasColumn('scores', 'total_questions')) {
                    $table->unsignedInteger('total_questions')->default(10);
                }
                if (!Schema::hasColumn('scores', 'time_seconds')) {
                    $table->unsignedInteger('time_seconds')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

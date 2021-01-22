<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttemptAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\QuizAttempt::class);
            $table->foreignIdFor(\App\Models\Question::class);
            $table->string("answer");
            $table->integer("poin")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attempt_answers');
    }
}

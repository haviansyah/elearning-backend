<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Task::class);
            $table->foreignIdFor(\App\Models\User::class);
            $table->string("answer");
            $table->dateTime("start_at");
            $table->dateTime("finished_at");
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
        Schema::dropIfExists('task_attempts');
    }
}

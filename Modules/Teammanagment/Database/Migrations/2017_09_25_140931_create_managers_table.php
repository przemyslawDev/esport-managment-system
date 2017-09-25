<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->string('nickname');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('managers_games', function (Blueprint $table) {
            $table->unsignedInteger('manager_id');
            $table->unsignedInteger('game_id');

            $table->foreign('manager_id')->references('id')->on('managers');
            $table->foreign('game_id')->references('id')->on('games');

            $table->primary(['manager_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers_games');
        Schema::dropIfExists('managers');
    }
}

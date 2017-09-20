<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('tag');
            $table->text('logo');
            $table->timestamps();
        });

        Schema::create('teams_games', function(Blueprint $table) {
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('game_id');

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('game_id')->references('id')->on('games');

            $table->primary(['team_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams_games');
        Schema::dropIfExists('teams');
    }
}

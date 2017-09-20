<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Teammanagment\Models\Game;

class AddGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $first_game = new Game;
        $first_game->name = 'League of Legends';
        $first_game->slug = 'LoL';
        $first_game->save();

        $second_game = new Game;
        $second_game->name = 'Counter Strike Global Offensive';
        $second_game->slug = 'CS:GO';
        $second_game->save();

        $third_game = new Game;
        $third_game->name = 'HearthStone';
        $third_game->slug = 'HS';
        $third_game->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}

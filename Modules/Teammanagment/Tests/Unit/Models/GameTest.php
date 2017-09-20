<?php

namespace Modules\Teammanagment\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Team;

class GameTest extends TestCase
{
    /** @test */
    public function game_success_test()
    {   
        $game = factory(Game::class)
            ->create()
            ->each(function ($g) {
                $g->teams()->save(factory(Team::class)->create());
            });

        $this->assertNotEmpty($game);
    }
}
<?php

namespace Modules\Teammanagment\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Team;
use Modules\Teammanagment\Models\Manager;
use Modules\Administration\Models\Employee;

class GameTest extends TestCase
{
    /** @test */
    public function teams_success_test()
    {   
        $game = factory(Game::class)->create();
        $team = factory(Team::class)->create();

        $game->teams()->save($team);

        $this->assertNotEmpty($game->teams);
    }

    /** @test */
    public function managers_success_test()
    {   
        $game = factory(Game::class)->create();
        $manager = factory(Manager::class)->create();

        $game->managers()->save($manager);

        $this->assertNotEmpty($game->managers);
    }
}
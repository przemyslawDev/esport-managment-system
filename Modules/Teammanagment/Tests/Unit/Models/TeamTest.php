<?php

namespace Modules\Teammanagment\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Teammanagment\Models\Team;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Manager;

class TeamTest extends TestCase
{
    /** @test */
    public function games_success_test()
    {   
        $team = factory(Team::class)->create();
        $game = factory(Game::class)->create();

        $team->games()->save($game);

        $this->assertNotEmpty($team->games);
    }

    /** @test */
    public function manager_success_test()
    {
        $team = factory(Team::class)->create();
        $manager = factory(Manager::class)->create();

        $team->manager()->associate($manager);

        $this->assertNotEmpty($team->manager);
    }
}
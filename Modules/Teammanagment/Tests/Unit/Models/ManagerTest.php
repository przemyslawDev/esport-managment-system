<?php

namespace Modules\Teammanagment\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Teammanagment\Models\Manager;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Employee;
use Modules\Teammanagment\Models\Team;

class ManagerTest extends TestCase
{
    /** @test */
    public function games_success_test()
    {   
        $manager = factory(Manager::class)->create();
        $game = factory(Game::class)->create();

        $manager->games()->save($game);

        $this->assertNotEmpty($manager->games);
    }

    /** @test */
    public function teams_success_test()
    {
        $manager = factory(Manager::class)->create();
        $team = factory(Team::class)->create();

        $manager->teams()->save($team);

        $this->assertNotEmpty($manager->teams);
    }
}
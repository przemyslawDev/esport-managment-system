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
    public function team_success_test()
    {   
        $game = factory(Game::class)
            ->create()
            ->each(function ($g) {
                $g->teams()->save(factory(Team::class)->create());
            });

        $this->assertNotEmpty($game);
    }

    /** @test */
    public function manager_success_test()
    {   
        $game = factory(Game::class)
            ->create()
            ->each(function ($g) {
                $g->managers()->save(factory(Manager::class)->create());
            });

        $this->assertNotEmpty($game);
    }
}
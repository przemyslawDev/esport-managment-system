<?php

namespace Modules\Teammanagment\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Teammanagment\Models\Team;
use Modules\Teammanagment\Models\Game;

class TeamTest extends TestCase
{
    /** @test */
    public function game_success_test()
    {   
        $team = factory(Team::class)
            ->create()
            ->each(function ($t) {
                $t->games()->save(factory(Game::class)->create());
            });

        $this->assertNotEmpty($team);
    }
}
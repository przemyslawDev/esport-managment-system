<?php

namespace Modules\Teammanagment\Tests\Unit\Models;

use Tests\TestCase;
use Modules\Teammanagment\Models\Manager;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Employee;

class ManagerTest extends TestCase
{
    /** @test */
    public function game_success_test()
    {   
        $manager = factory(Manager::class)
            ->create()
            ->each(function ($m) {
                $m->games()->save(factory(Game::class)->create());
            });

        $this->assertNotEmpty($manager);
    }
}
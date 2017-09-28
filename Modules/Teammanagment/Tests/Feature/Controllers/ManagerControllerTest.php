<?php

namespace Modules\Teammanagment\Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Manager;
use Tests\TestCase;

class ManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function getAll_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        factory(Manager::class,12)->create();

        $count = Manager::count();

        $response = $this->json('get', '/teammanagment/managers/get/all')
            ->assertSuccessful()->decodeResponseJson();

        $this->assertEquals($count, $response['total']);
    }

    /** @test */
    public function getAll_filter_games_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        factory(Manager::class,12)->create();

        $first_game = Game::first();

        factory(Manager::class, 10)->create()->each(function ($m) use ($first_game){
            $m->games()->attach($first_game);
        });

        $second_game = Game::skip(1)->first();

        factory(Manager::class, 5)->create()->each(function ($m) use ($second_game) {
            $m->games()->attach($second_game);
        });

        $response = $this->json('get', '/teammanagment/managers/get/all?games[]=' . $first_game->id .
            '&games[]=' . $second_game->id)
            ->assertSuccessful()->decodeResponseJson();

        $this->assertEquals(15, $response['total']);
    }

    /** @test */
    public function getAll_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->json('get', '/teammanagment/managers/get/all')->assertStatus(403);
    }

    /** @test */
    public function getAll_as_manager_response_permission_error_test()
    {
        $this->createManager();

        $this->json('get', '/teammanagment/managers/get/all')->assertStatus(403);
    }

    /** @test */
    public function getAll_as_guest_response_permission_error_test()
    {
        $this->json('get', '/teammanagment/managers/get/all')->assertStatus(401);
    }
}
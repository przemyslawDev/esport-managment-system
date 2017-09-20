<?php

namespace Modules\TeamManagment\Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TeamManagment\Models\Game;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_reponse_success_test()
    {
        $this->createSystemAdmin();

        $this->get('/teammanagment/games')->assertSuccessful()
            ->assertViewIs('teammanagment::games.index');
    }

    /** @test */
    public function index_as_administrator_permission_error_test()
    {
        $this->createAdmin();

        $this->get('/teammanagment/games')->assertRedirect('/403');
    }

    /** @test */
    public function index_as_guest_permission_error_test()
    {
        $this->get('/teammanagment/games')->assertRedirect('/');
    }

    /** @test */
    public function show_reponse_success_test()
    {
        $this->createSystemAdmin();

        $game = factory('Modules\TeamManagment\Models\Game')->create();

        $this->get('/teammanagment/games' . '/' . $game->id)->assertSuccessful()
            ->assertViewIs('teammanagment::games.show')->assertViewHas('id', $game->id);
    }   

    /** @test */
    public function show_as_administrator_permission_error_test()
    {
        $this->createAdmin();

        $game = factory('Modules\TeamManagment\Models\Game')->create();

        $this->get('/teammanagment/games' . '/' . $game->id)->assertRedirect('/403');
    }

    /** @test */
    public function show_as_guest_permission_error_test()
    {
        $game = factory('Modules\TeamManagment\Models\Game')->create();

        $this->get('/teammanagment/games' . '/' . $game->id)->assertRedirect('/');
    }   

    /** @test */
    public function get_response_success_test()
    {
        $this->createSystemAdmin();

        $game = factory('Modules\TeamManagment\Models\Game')->create();

        $response = $this->json('get', '/teammanagment/games/game' . '/' . $game->id)
            ->assertSuccessful()->decodeResponseJson();
        
        $this->assertNotEmpty($response);
    }

    /** @test */
    public function get_as_administrator_response_permission_error_test()
    {
        $this->createAdmin();

        $game = factory('Modules\TeamManagment\Models\Game')->create();

        $this->json('get', '/teammanagment/games/game' . '/' . $game->id)
            ->assertStatus(403);
    }

    /** @test */
    public function get_as_guest_response_permission_error_test() 
    {   
        $game = factory('Modules\TeamManagment\Models\Game')->create();
        
        $this->json('get', '/teammanagment/games/game' . '/' . $game->id)
            ->assertStatus(401);
    }
}
<?php

namespace Modules\Teammanagment\Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Teammanagment\Models\Game;

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
    public function getAll_response_success_test()
    {
        $this->createSystemAdmin();

        $count = Game::count();

        $response = $this->json('get', '/teammanagment/games/get/all')
            ->assertSuccessful()->decodeResponseJson();

        $this->assertEquals($count, $response['total']);
    }

    /** @test */
    public function getAll_as_administrator_response_permission_error_test()
    {
        $this->createAdmin();
        
        $this->json('get', '/teammanagment/games/get/all')
            ->assertStatus(403);
    }

    /** @test */
    public function getAll_as_guest_response_permission_error_test()
    {
        $this->json('get', '/teammanagment/games/get/all')
            ->assertStatus(401);
    }

    /** @test */
    public function show_reponse_success_test()
    {
        $this->createSystemAdmin();

        $game = factory(Game::class)->create();

        $this->get('/teammanagment/games' . '/' . $game->id)->assertSuccessful()
            ->assertViewIs('teammanagment::games.show')->assertViewHas('id', $game->id);
    }   

    /** @test */
    public function show_as_administrator_permission_error_test()
    {
        $this->createAdmin();

        $game = factory(Game::class)->create();

        $this->get('/teammanagment/games' . '/' . $game->id)->assertRedirect('/403');
    }

    /** @test */
    public function show_as_guest_permission_error_test()
    {
        $game = factory(Game::class)->create();

        $this->get('/teammanagment/games' . '/' . $game->id)->assertRedirect('/');
    }   

    /** @test */
    public function get_response_success_test()
    {
        $this->createSystemAdmin();

        $game = factory(Game::class)->create();

        $response = $this->json('get', '/teammanagment/games/game' . '/' . $game->id)
            ->assertSuccessful()->decodeResponseJson();
        
        $this->assertNotEmpty($response);
    }

    /** @test */
    public function get_as_administrator_response_permission_error_test()
    {
        $this->createAdmin();

        $game = factory(Game::class)->create();

        $this->json('get', '/teammanagment/games/game' . '/' . $game->id)
            ->assertStatus(403);
    }

    /** @test */
    public function get_as_guest_response_permission_error_test() 
    {   
        $game = factory(Game::class)->create();
        
        $this->json('get', '/teammanagment/games/game' . '/' . $game->id)
            ->assertStatus(401);
    }
}
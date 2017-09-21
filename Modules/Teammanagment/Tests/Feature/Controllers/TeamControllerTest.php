<?php

namespace Modules\Teammanagment\Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Teammanagment\Models\Team;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $this->get('/teammanagment/teams')->assertSuccessful()
            ->assertViewIs('teammanagment::teams.index');
    }

    /** @test */
    public function index_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->get('/teammanagment/teams')->assertRedirect('/403');
    }

    /** @test */
    public function index_as_guest_response_permission_error_test()
    {
        $this->get('/teammanagment/teams')->assertRedirect('/');
    }

    /** @test */
    public function getAll_as_system_admin_response_success_test()
    {   
        $this->createSystemAdmin();

        $count = Team::count();

        $response = $this->json('get','/teammanagment/teams/get/all')
            ->assertSuccessful()->decodeResponseJson();

        $this->assertEquals($count, $response['total']);
    }

    /** @test */
    public function getAll_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->json('get','/teammanagment/teams/get/all')->assertStatus(403);
    }

    /** @test */
    public function getAll_as_guest_response_permission_error_test()
    {
        $this->json('get','/teammanagment/teams/get/all')->assertStatus(401);
    }

    /** @test */
    public function create_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $this->get('/teammanagment/teams/create')->assertSuccessful()
            ->assertViewIs('teammanagment::teams.create');
    }

    /** @test */
    public function create_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->get('/teammanagment/teams/create')->assertRedirect('/403');
    }

    /** @test */
    public function create_as_guest_response_permission_error_test()
    {
        $this->get('/teammanagment/teams/create')->assertRedirect('/');
    }

    /** @test */
    public function show_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $team = factory(Team::class)->create();

        $this->get('/teammanagment/teams' . '/' . $team->id)->assertSuccessful()
            ->assertViewIs('teammanagment::teams.show')->assertViewHas('id', $team->id);
    }

    /** @test */
    public function show_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $team = factory(Team::class)->create();
        
        $this->get('/teammanagment/teams' . '/' . $team->id)->assertredirect('/403');
    }

    /** @test */
    public function show_as_guest_response_permission_error_test()
    {
        $team = factory(Team::class)->create();
        
        $this->get('/teammanagment/teams' . '/' . $team->id)->assertRedirect('/');
    }

    /** @test */
    public function get_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $team = factory(Team::class)->create();

        $response = $this->json('get', '/teammanagment/teams/team' . '/' . $team->id)
            ->assertSuccessful()->decodeResponseJson();

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function get_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $team = factory(Team::class)->create();

        $this->json('get', '/teammanagment/teams/team' . '/' . $team->id)
            ->assertStatus(403);
    }

    /** @test */
    public function get_as_guest_response_permission_error_test()
    {
        $team = factory(Team::class)->create();

        $this->json('get', '/teammanagment/teams/team' . '/' . $team->id)
            ->assertStatus(401);
    }

    /** @test */
    public function store_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $data = [
            'name' => 'test',
            'tag' => 'test',
            'games' => [1]
        ];

        $response = $this->json('post', '/teammanagment/teams', $data)
            ->assertSuccessful()->decodeResponseJson();
        
        $this->assertDatabaseHas('teams', [
            'name' => $data['name'],
            'tag' => $data['tag']
        ]);
        
        foreach($data['games'] as $game_id) {
            $this->assertDatabaseHas('teams_games', [
                'team_id' => $response['id'],
                'game_id' => $game_id
            ]);
        }
    }

    /** @test */
    public function store_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->json('post', '/teammanagment/teams', [])->assertStatus(403);
    }

    /** @test */
    public function store_as_guest_response_permission_error_test()
    {
        $this->json('post', '/teammanagment/teams', [])->assertStatus(401);
    }

    /** @test */
    public function store_response_validation_fail_test()
    {
        $this->createSystemAdmin();

        $data = collect([
            'name' => 'test',
            'tag' => 'test',
            'games' => [1]
        ]);

        $response = $this->json('post', '/teammanagment/teams', $data->except('name')->toArray())
            ->assertStatus(422)->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.']
                ]
            ]);

        $response = $this->json('post', '/teammanagment/teams', $data->except('tag')->toArray())
            ->assertStatus(422)->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'tag' => ['The tag field is required.']
                ]
            ]);
    }

    /** @test */
    public function edit_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $team = factory(Team::class)->create();

        $this->get('/teammanagment/teams' . '/' . $team->id . '/edit')
            ->assertSuccessful()->assertViewIs('teammanagment::teams.edit')
            ->assertViewHas('id', $team->id);
    }

    /** @test */
    public function edit_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $team = factory(Team::class)->create();

        $this->get('/teammanagment/teams' . '/' . $team->id . '/edit')
            ->assertRedirect('/403');
    }

    /** @test */
    public function edit_as_guest_response_permission_error_test()
    {
        $team = factory(Team::class)->create();

        $this->get('/teammanagment/teams' . '/' . $team->id . '/edit')
            ->assertRedirect('/');
    }

    /** @test */
    public function update_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $team = factory(Team::class)->create();

        $data = [
            'name' => 'test',
            'tag' => 'test'
        ];

        $response = $this->json('put', 'teammanagment/teams' . '/' . $team->id, $data)
            ->assertSuccessful()->decodeResponseJson();

        $this->assertDatabaseHas('teams', [
            'id' => $response['id'],
            'name' => $data['name'],
            'tag' => $data['tag']
        ]);
    }

    /** @test */
    public function update_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $team = factory(Team::class)->create();

        $this->json('put', 'teammanagment/teams' . '/' . $team->id, [])
            ->assertStatus(403);
    }

    /** @test */
    public function update_as_guest_response_permission_error_test()
    {
        $team = factory(Team::class)->create();
        
        $this->json('put', 'teammanagment/teams' . '/' . $team->id, [])
            ->assertStatus(401);
    }

    /** @test */
    public function update_response_validation_fail_test()
    {
        $this->createSystemAdmin();
        
        $team = factory(Team::class)->create();

        $data = collect([
            'name' => 'test',
            'tag' => 'test'
        ]);
        
        $this->json('put', 'teammanagment/teams' . '/' . $team->id, $data->except('name')->toArray())
            ->assertStatus(422)->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.']
                ]
            ]);

        $this->json('put', 'teammanagment/teams' . '/' . $team->id, $data->except('tag')->toArray())            
            ->assertStatus(422)->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'tag' => ['The tag field is required.']
                ]
            ]);
    }

    /** @test */
    public function destroy_as_system_admin_response_success_test()
    {
        $this->createSystemAdmin();

        $team = factory(Team::class)->create();

        $this->json('delete', '/teammanagment/teams' . '/' . $team->id)
            ->assertSuccessful();

        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
            'name' => $team->name
        ]);
    }

    /** @test */
    public function destroy_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $team = factory(Team::class)->create();
        
        $this->json('delete', '/teammanagment/teams' . '/' . $team->id)
            ->assertStatus(403);
    }

    /** @test */
    public function destory_as_guest_response_permission_error_test()
    {
        $team = factory(Team::class)->create();
        
        $this->json('delete', '/teammanagment/teams' . '/' . $team->id)
            ->assertStatus(401);
    }
}
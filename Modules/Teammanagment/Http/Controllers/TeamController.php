<?php

namespace Modules\Teammanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use Modules\Teammanagment\Models\Team;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Models\Manager;
use Modules\Teammanagment\Http\Requests\StoreTeamRequest;
use Modules\Teammanagment\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{
    public function index()
    {
        return view('teammanagment::teams.index');
    }

    public function getAll() 
    {
        $teams = Team::with(['games', 'manager'])->paginate();

        return response()->json($teams);
    }

    public function create()
    {
        return view('teammanagment::teams.create');
    }

    public function store(StoreTeamRequest $request)
    {
        $team = new Team();
        $team->name = $request->input('name');
        $team->tag = $request->input('tag');
        $team->save();

        foreach($request->input('games') as $game_id) {
            $game = Game::find($game_id);
            $team->games()->save($game);
        }

        return response()->json($team);
    }

    public function get($id) 
    {
        $team = Team::with(['games', 'manager'])->find($id);

        return response()->json($team);
    }

    public function show($id)
    {
        return view('teammanagment::teams.show')->with('id', $id);
    }

    public function edit($id)
    {
        return view('teammanagment::teams.edit')->with('id', $id);
    }

    public function update($id, UpdateTeamRequest $request)
    {
        $team = Team::findOrFail($id);
        $team->name = $request->input('name');
        $team->tag = $request->input('tag');
        $team->save();

        return response()->json($team);
    }

    public function destroy($id)
    {
        Team::destroy($id);
    }

    public function attachGame($team_id, $game_id)
    {
        $team = Team::findOrFail($team_id);
        $team->games()->attach($game_id);
    }

    public function detachGame($team_id, $game_id) 
    {
        $team = Team::findOrFail($team_id);
        $team->games()->detach($game_id);
    }

    public function attachManager($team_id, $manager_id = null)
    {
        $team = Team::findOrFail($team_id);

        if(!$manager_id) {
            $user = Auth::user();
            if($user->hasRole('manager')) {
                $manager = $user->employee->manager()->first();
                $team->manager()->associate($manager);
                $team->save();
            }
        } else {
            $team->manager()->associate($manager_id);
            $team->save();
        }
    }

    public function detachManager($team_id)
    {
        $team = Team::findOrFail($team_id);

        $team->manager()->dissociate();
        $team->save();

    }
}

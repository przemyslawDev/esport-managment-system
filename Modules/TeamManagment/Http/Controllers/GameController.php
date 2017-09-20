<?php

namespace Modules\Teammanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Teammanagment\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        return view('teammanagment::games.index');
    }

    public function show($id)
    {
        return view('teammanagment::games.show')->with('id', $id);
    }

    public function get($id) 
    {
        $game = Game::findOrFail($id)->first();

        return response()->json($game);
    }
}

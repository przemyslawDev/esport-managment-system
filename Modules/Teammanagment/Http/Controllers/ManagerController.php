<?php

namespace Modules\Teammanagment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\Teammanagment\Models\Manager;

class ManagerController extends Controller
{
    public function getAll(Manager $manager)
    {
        if(Input::has('games')) {
            $games = Input::get('games');

            $manager = $manager->wherehas('games', function($query) use ($games) {
               $query->whereIn('id', $games);
            });
        }

        $managers = $manager->paginate();

        return response()->json($managers);
    }
}

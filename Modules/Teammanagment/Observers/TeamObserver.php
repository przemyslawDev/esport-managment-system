<?php

namespace Modules\Teammanagment\Observers;

use Modules\Teammanagment\Models\Team;

class TeamObserver
{

    /**
     * Listen to the Team deleting event.
     *
     * @param  Team  $team
     * @return void
     */
    public function deleting(Team $team)
    {
        $team->games()->detach();
    }
}
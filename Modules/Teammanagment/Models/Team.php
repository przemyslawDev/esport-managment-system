<?php

namespace Modules\Teammanagment\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    public function games()
    {
        return $this->belongsToMany('Modules\Teammanagment\Models\Game', 'teams_games', 'team_id', 'game_id');
    }

    public function manager()
    {
        return $this->belongsTo('Modules\Teammanagment\Models\Manager');
    }
}

<?php

namespace Modules\Teammanagment\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany('Modules\Teammanagment\Models\Team', 'teams_games', 'game_id', 'team_id');
    } 
}
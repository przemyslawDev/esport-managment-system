<?php

namespace Modules\Teammanagment\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo('Modules\Administration\Models\Employee', 'id');
    }

    public function games()
    {
        return $this->belongsToMany('Modules\Teammanagment\Models\Game', 'managers_games', 'manager_id', 'game_id');
    }

    public function teams()
    {
        return $this->hasMany('Modules\Teammanagment\Models\Team');
    }
}

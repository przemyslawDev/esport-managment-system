<?php

namespace Modules\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Teammanagment\Models\Traits\ManagerTrait;

class Employee extends Model
{
    use ManagerTrait;

    protected $guarded = [];

    public function user() 
    {
        return $this->belongsTo('App\User');
    }
}

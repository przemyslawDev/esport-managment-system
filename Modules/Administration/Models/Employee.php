<?php

namespace Modules\Administration\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = ['user_id'];

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

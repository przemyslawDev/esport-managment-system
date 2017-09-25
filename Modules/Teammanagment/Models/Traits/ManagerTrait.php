<?php

namespace Modules\Teammanagment\Models\Traits;

trait ManagerTrait 
{
    public function manager()
    {
        return $this->hasOne('Modules\Teammanagment\Models\Manager', 'employee_id');        
    }
}
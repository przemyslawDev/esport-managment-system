<?php

namespace Modules\Administration\Models\Traits;

trait EmployeeTrait 
{
    public function employee() 
    {
        return $this->hasOne('Modules\Administration\Models\Employee');
    }
}
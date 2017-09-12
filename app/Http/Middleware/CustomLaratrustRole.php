<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Laratrust\Middleware\LaratrustRole;
use App\Exceptions\DontHavePermissionException;

class CustomLaratrustRole extends LaratrustRole
{
    public function handle($request, Closure $next, $roles, $team = null, $requireAll = false)
    {
        list($team, $requireAll) = $this->assignRealValuesTo($team, $requireAll);
        
        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if ($this->auth->guest() || !$request->user()->hasRole($roles, $team, $requireAll)) {
            throw new DontHavePermissionException('Unauthenticated.');
        }

        return $next($request);
    }

    private function assignRealValuesTo($team, $requireAllOrOptions)
    {
        return [
            ($team == 'require_all' ? null : $team),
            ($team == 'require_all' ? true : ($requireAllOrOptions== 'require_all' ? true : false)),
        ];
    }
}

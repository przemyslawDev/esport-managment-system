<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Laratrust\Middleware\LaratrustPermission;

class CustomLaratrustPermission extends LaratrustPermission
{
    public function handle($request, Closure $next, $permissions, $team = null, $requireAll = false)
    {
        list($team, $requireAll) = $this->assignRealValuesTo($team, $requireAll);

        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }

        if ($this->auth->guest() || !$request->user()->hasPermission($permissions, $team, $requireAll)) {
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
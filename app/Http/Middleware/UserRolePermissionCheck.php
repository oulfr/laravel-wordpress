<?php

namespace App\Http\Middleware;

use Closure;
use Flugg\Responder\Exceptions\Http\UnauthorizedException;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRolePermissionCheck
 * @package App\Http\Middleware
 */
class UserRolePermissionCheck
{
    /**
     * @param $request
     * @param Closure $next
     * @param $roleOrPermission
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            throw new UnauthorizedException();
        }

        $rolesOrPermissions = is_array($roleOrPermission) ? $roleOrPermission : explode('|', $roleOrPermission);

        if (!Auth::guard($guard)->user()->hasAnyCap($rolesOrPermissions) && !Auth::guard($guard)->user()->isAdmin()) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}

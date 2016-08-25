<?php namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Facade\API;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class RouteNeedsRole
 * @package App\Http\Middleware
 */
class RouteNeedsRole {

    /**
     * @param $request
     * @param callable $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!access()->hasRole($role)) {
            API::response()->errorForbidden('没有权限');
        }

        return $next($request);
    }
}

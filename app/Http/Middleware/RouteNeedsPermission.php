<?php namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Facade\API;

/**
 * Class RouteNeedsRole
 * @package App\Http\Middleware
 */
class RouteNeedsPermission {

    /**
     * @param $request
     * @param callable $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if ( ! access()->can($permission)) {
            API::response()->errorForbidden('没有权限');
        }

        return $next($request);
    }
}

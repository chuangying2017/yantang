<?php namespace App\Api\Middleware;

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
        if ( ! access()->hasRole($role)) {
            return new RedirectResponse('http://e-grace.com.cn/');
//            API::response()->errorForbidden('没有权限');
        }

        return $next($request);
    }
}

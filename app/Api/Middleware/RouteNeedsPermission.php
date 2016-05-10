<?php namespace App\Api\Middleware;

use Closure;
use Dingo\Api\Facade\API;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            return new RedirectResponse('http://e-grace.com.cn/');
        }

        return $next($request);
    }
}

<?php namespace App\Http\Middleware;

use Closure;

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
        if ( ! access()->can($permission))
            return redirect('/')->withFlashDanger("权限不足");

        return $next($request);
    }
}

<?php namespace App\Http\Middleware;

use Closure;

/**
 * Class RemoveAcrossControl
 * @package App\Http\Middleware
 */
class RemoveAcrossControl
{
    public function handle($request, Closure $next)
    {
        header_remove('Access-Control-Allow-Credentials');
        header_remove('Access-Control-Allow-Origin');

        return $next($request);
    }
}

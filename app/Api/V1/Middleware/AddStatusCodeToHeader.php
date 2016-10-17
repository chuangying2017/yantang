<?php

namespace App\Api\V1\Middleware;

use Closure;

class AddStatusCodeToHeader {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('status', $response->status());

        return $response;
    }
}

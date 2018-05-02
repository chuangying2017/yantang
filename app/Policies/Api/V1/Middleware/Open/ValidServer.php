<?php

namespace App\Api\V1\Middleware\Open;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ValidServer {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_api = $request->getClientIp();

        // info($request_api);
        
        
        
        if (in_array(
            $request_api,
            explode('|', env('OPEN_API_SERVER'))
        )) {
            return $next($request);
        }
        
        file_put_contents('request_api.txt', $request_api.'/'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
        
        throw new AccessDeniedHttpException();
    }
}

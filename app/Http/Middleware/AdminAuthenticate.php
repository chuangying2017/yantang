<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;

class AdminAuthenticate {

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            if ($request->ajax() || $request->is('api/*')) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->to('/auth/login');
            }
        }

        return $next($request);
    }

}

<?php

namespace App\Api\Middleware;

use Closure;

use Carbon\Carbon;

class Timing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

      if( Carbon::now() < Carbon::createFromFormat('Y-m-d H:i:s', env('EVENT_START_TIME')) )
      {
        return view('message', ['message' => trans('app.not_start')]);
      }
      if( Carbon::now() > Carbon::createFromFormat('Y-m-d H:i:s', env('EVENT_END_TIME')) )
      {
        return view('message', ['message' => trans('app.is_end')]);
      }

      return $next($request);
    }
}

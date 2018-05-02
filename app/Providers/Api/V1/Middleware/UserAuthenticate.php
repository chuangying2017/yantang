<?php namespace App\Api\Middleware;

use Closure;
use Auth;

class UserAuthenticate {


	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->auth = Auth::user();
	}


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if ($this->auth->guest())
		{
			return redirect()->guest('auth/login');
		}

		return $next($request);
	}

}

<?php namespace App\Http\Middleware;

use Closure;
use Auth;

class WechatAuthenticate {

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

		if ( ! $this->auth->check())
		{
			$return_url = $request->fullUrl();
			return redirect()->action('Auth\WechatAuthController@getIndex', ['return' => $return_url, 'auth_type' => 'snsapi_userinfo']);
		}

		return $next($request);
	}


}

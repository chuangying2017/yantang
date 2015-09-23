<?php namespace App\Http\Middleware;

use Closure;
use \Auth;
use \Wechat;
use Cache;

class RedirectIfNotFollow {

	protected $auth;

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

			if( $request->input('from') != md5('') ) {
				return redirect(
					''
				)->header('Cache-Control', 'no-store');
			}

//		if ( $openid = $this->auth->get()->openid ) {
//
//			$user_info = Wechat::getUserInfo($openid);
//
//			if( $user_info['subscribe'] == 0 || !isset($user_info['openid'])) {
//				return redirect(
//					'http://mp.weixin.qq.com/s?__biz=MjM5NzY3NTcxMA==&mid=211973666&idx=1&sn=bcfc96ff10e15631cb075f7bfff5d9ab#rd'
//				)->header('Cache-Control', 'no-store');
//			}
//		}

		return $next($request);
	}

	protected function setCache($cachename,$value,$expired){
		$expiresAt = floor($expired / 60);
		Cache::put($cachename, $value, $expiresAt);
		Cache::put($value, time() + $expiresAt * 60, $expiresAt);
	}


}

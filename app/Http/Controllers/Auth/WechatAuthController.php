<?php namespace App\Http\Controllers\Auth;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Wechat\Exceptions\WechatException;

use Illuminate\Http\Request;
use Wechat;
use App\Models\User;
use Auth;

class WechatAuthController extends Controller {


	const AUTH_BASE_TYPE = 'snsapi_base';
	const AUTH_USERINFO_TYPE = 'snsapi_userinfo';

	/**
	 * 网页验证， URL格式为 /auth?return=需要获取openid的网址
	 *
	 * @return Redirect Wechat Auth Redirct
	 */
	public function getIndex(Request $request)
	{

		$type = $request->get('auth_type', self::AUTH_BASE_TYPE);

		$return_url = $request->get('return');

		//设置微信授权回调地址，使用基本授权模式
		$real_callback = action('Auth\WechatAuthController@getRedirect') . '?return=' . base64_encode($return_url);

		$callback = Wechat::authCallback($real_callback);
		$red_url = Wechat::getOauthRedirect($callback, 'STATE', $type);
		return redirect()->to($red_url);

	}

	/**
	 * 微信授权回调，附带access_token、code参数, 获取openid并跳回原地址
	 *
	 * @return
	 */
	public function getRedirect(Request $request)
	{
		//获取token信息，从中获取openid
		$token = Wechat::getOauthAccessToken();

		if( empty($token['openid']) ){
			throw new WechatException(Wechat::getErrMsg(), Wechat::getErrCode());
		}

		//获取该openid在数据库中的ID，并登陆用户后重定向至原地址
		$user_id = $this->getUserId($token);

		if( ! $user_id){
			throw new WechatException(Wechat::getErrMsg(), Wechat::getErrCode());
		}

		Auth::user()->loginUsingId($user_id);

		$return_url = base64_decode($request->get('return'));

		\Log::info($return_url);
		return redirect()->to($return_url);

	}

	/**
	 * 通过openid查询是否曾近授权，未授权则拉取用户信息并写入数据库
	 *
	 * @param  array $token 微信拉取的token信息
	 * @return Object | Boolean  用户对象，出错返回false
	 */
	public function getUserId($token)
	{
		$openid = $token['openid'];
		$user = User::where('openid', $openid)->first();

		if($token['scope'] == self::AUTH_BASE_TYPE) {
			if($user) {
				return $user->id;
			}
			$user = User::firstOrCreate(['openid' => $openid]);
			$user->save();
			return $user->id;
		}
		//从微信获取用户信息成功后，再把用户信息写入数据库并返回ID
		if($user && $user->nickname) {
			return $user->id;
		}
		$access_token = $token['access_token'];
		$user_info = Wechat::getOauthUserinfo($access_token, $openid);
		if($user_info) {
			$user_info = array_except($user_info, ['privilege', 'unionid']);
			$user = User::updateOrCreate(['openid' => $user_info['openid']], $user_info);

			return $user->id;
		}
	}


}

<?php 
namespace App\Library\Wechat;


use Illuminate\Support\ServiceProvider;

class WechatServiceProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('wechat_main', function(){
			return new MyWechatService(array(
				'appid' => env('WECHAT_APPID'),
				'appsecret' => env('WECHAT_APPSECRET'),
				'auth_token_id' => env('WECHAT_AUTH_TOKEN_ID'),
				'auth_token' => env('WECHAT_AUTH_TOKEN'),
			));
		});

		$this->app->singleton('wechat_subscribe', function(){
			return new MyWechatService(array(
				'appid' => env('WECHAT_APPID'),
				'appsecret' => env('WECHAT_APPSECRET'),
				'auth_token_id' => env('WECHAT_AUTH_TOKEN_ID'),
				'auth_token' => env('WECHAT_AUTH_TOKEN'),
			));
		});
	}
	
}
<?php  namespace App\Library\Wechat;

use App\Http\Requests;
use Request;
use View;
use Wechat;


trait WechatSign {

	protected function setupWechatSignPackage()
	{
		$url = Request::fullUrl();

		if($url == route('/'))
		{
			$url .= '/';
		}

		$signPackage = app()->make('wechat_subscribe')->getJsSign($url);

		View::share('signPackage', $signPackage);
	}

}
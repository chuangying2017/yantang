<?php  namespace App\Library\Wechat;

use App\Http\Requests;
use Request;
use View;
use Wechat;


trait WechatSign {

	protected function setupWechatSignPackage()
	{
		$base_url = Request::url();
		$full_url = Request::fullUrl();
		if($base_url == url('/'))
		{
			$url =  $base_url . '/' . substr($full_url, strlen($base_url));
		} else {
			$url = $full_url;
		}

		$signPackage = app()->make('wechat_subscribe')->getJsSign($url);

		View::share('signPackage', $signPackage);
	}

}
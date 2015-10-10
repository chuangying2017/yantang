<?php 
namespace App\Library\Wechat;

use \Cache;
use \Carbon\Carbon;

class MyWechatService extends WechatService {


	
	const CONNECT_URL_PREFIX = 'http://auth.weazm.com/wechat/public/wechat';
	const CONNECT_ACCESS_TOKEN_URL= '/access-token';
	const CONNECT_JS_API_TICKET_URL= '/js-ticket';
	const CONNECT_REDIRECT_URL = '/redirect';
	const CONNECT_OAUTH_TOKEN_URL = '/oauth-token';


	private $auth_token;
	private $auth_token_id;
	

	public function __construct($options)
	{
		parent::__construct($options);
	
		$this->auth_token = isset($options['auth_token'])?$options['auth_token']:'';
		$this->auth_token_id = isset($options['auth_token_id'])?$options['auth_token_id']:'';
	}



	/**
	 * 设置缓存，按需重载
	 * @param string $cachename
	 * @param mixed $value
	 * @param int $expired | seconds
	 * @return boolean
	 */
	protected function setCache($cachename,$value,$expired){
		$expiresAt = floor($expired / 60);
		Cache::put($cachename, $value, $expiresAt);
		Cache::put($value, time() + $expiresAt * 60, $expiresAt);
	}

	/**
	 * 获取缓存，按需重载
	 * @param string $cachename
	 * @return mixed
	 */
	protected function getCache($cachename){
		return Cache::get($cachename);
	}

	/**
	 * 清除缓存，按需重载
	 * @param string $cachename
	 * @return boolean
	 */
	protected function removeCache($cachename){
		return Cache::forget($cachename);
	}

	public function getAuthExpires($expires_name = ''){

		if($expires_name) {
			return $this->getCache($expires_name) - time();
		}

		$expires_name = $this->checkAuth();

		if( ! $expires_name) return false;

		return $this->getCache($expires_name) - time();

	}
	

	public function getTicketExpires($expires_name = ''){

		if($expires_name) {
			return $this->getCache($expires_name) - time();
		}

		$expires_name = $this->getJsTicket();
		
		if( ! $expires_name) return false;

		return $this->getCache($expires_name) - time();

	}
	


	private function getSignatureUri()
	{
		$timestamp = time();
		$nonce = $this->generateNonceStr();

		$tmpArr = array($timestamp, $nonce, $this->auth_token);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$signature = sha1( $tmpStr );

		return '?token_id='.$this->auth_token_id.'&timestamp='.$timestamp.'&nonce='.$nonce.'&signature='.$signature;

	}

	private function getAccessTokenUrl()
	{
		return self::CONNECT_URL_PREFIX . self::CONNECT_ACCESS_TOKEN_URL . $this->getSignatureUri();
	}

	private function getJsTicketUrl()
	{
		return self::CONNECT_URL_PREFIX . self::CONNECT_JS_API_TICKET_URL . $this->getSignatureUri();
	}

	public function authCallback($callback)
	{
		return self::CONNECT_URL_PREFIX . self::CONNECT_REDIRECT_URL . '?red_url=' . base64_encode($callback);
	}

	/**
	 * 获取access_token
	 * @param string $appid 如在类初始化时已提供，则可为空
	 * @param string $appsecret 如在类初始化时已提供，则可为空
	 * @param string $token 手动指定access_token，非必要情况不建议用
	 */
	public function checkAuth($appid='',$appsecret='',$token=''){

		if (!$appid || !$appsecret) {
			$appid = $this->appid;
		}
		
		 $authname = 'wechat_access_token'.$appid;
		 if ($rs = $this->getCache($authname))  {
		 	$this->access_token = $rs;
		 	return $rs;
		 }

		$result = $this->http_get($this->getAccessTokenUrl());

		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || isset($json['errcode'])) {
				$this->errCode = $json['errcode'];
				return false;
			}
			$this->access_token = $json['access_token'];
			 $expire = $json['expires_in'] ? intval($json['expires_in']) : 3600;
			 $this->removeCache($authname);
			 $this->setCache($authname,$this->access_token,$expire);
			return $this->access_token;
		}
		return false;
	}


	/**
	 * 获取JSAPI授权TICKET
	 * @param string $appid 用于多个appid时使用,可空
	 * @param string $jsapi_ticket 手动指定jsapi_ticket，非必要情况不建议用
	 */
	public function getJsTicket($appid='',$jsapi_ticket=''){
		if (!$appid) $appid = $this->appid;

		$authname = 'wechat_jsapi_ticket'.$appid;
		if ($rs = $this->getCache($authname))  {
			$this->jsapi_ticket = $rs;
			return $rs;
		}

		$result = $this->http_get($this->getJsTicketUrl());
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			}
			$this->jsapi_ticket = $json['ticket'];
			$expire = $json['expires_in'] ? intval($json['expires_in']) : 3600;
			$this->setCache($authname,$this->jsapi_ticket,$expire);
			return $this->jsapi_ticket;
		}
		return false;
	}


	public function getErrMsg()
	{
		return $this->errMsg;
	}


	public function getErrCode()
	{
		return $this->errCode;
	}

	public function getMediaDownloadUrl($media_id)
	{
		$url = self::UPLOAD_MEDIA_URL.self::MEDIA_GET_URL.'access_token='.$this->access_token.'&media_id='.$media_id;

		return $url;
	}




}
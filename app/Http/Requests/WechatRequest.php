<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Wechat;

class WechatRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      // $remote_ip = $_SERVER['REMOTE_ADDR'];
      // info(config('wechat.ips'));
      // if( ! in_array($remote_ip, config('wechat.ips') ))
      // {
      //   if( ! $wecat_ips = Wechat::getServerIp())
      //   {
      //    info('get server ip wrong, code: ' . Wechat::getErrCode()); 
      //    return true;
      //   } else {
      //     if( ! in_array($remote_ip, $wecat_ips) ) 
      //     {
      //       return false;
      //     }
      //   } 
      // } 

      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Api\Middleware;

use Closure;

class ValidatorUserRegisterPhone {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('phone', null);
        //验证手机验证码
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|confirm_mobile_not_change:' . $token,
            'code'  => 'required|verify_code:' . $token,
        ]);
        if ($validator->fails()) {
            //验证失败后建议清空存储的短信发送信息，防止用户重复试错
//            \SmsManager::forgetSentInfo();

            throw new \Dingo\Api\Exception\StoreResourceFailedException('手机验证失败', $validator->errors());
        }

        return $next($request);
    }

}

<?php

return [

    /*
     * -----------------------------------
     * 是否数据库记录日志
     * -----------------------------------
     */
    'database_enable' => true,

    /*
     * -----------------------------------
     * 验证码发送前合法性验证
     * -----------------------------------
     */
    'verify' => [
        'mobile' => [
            'enable' => false,

            //default rule
            'use' => 'check_mobile_exists',

            //available rules
            'rules' => [

                'mobile_required' => 'required|zh_mobile',

                'check_mobile_unique' => 'required|zh_mobile|unique:users,mobile',

                'check_mobile_exists' => 'required|zh_mobile|exists:users,phone',

                //add your rules here...
            ]
        ]
    ],

    /*
     * -----------------------------------
     * 验证码模块提示信息
     * -----------------------------------
     */
    'notifies' => [
        // 频繁请求无效的提示
        'request_invalid' => "请求无效，请在%s秒后重试",

        // 验证码短信发送失败的提示
        'sms_send_failure' => "短信验证码发送失败，请稍后重试",

        // 语音验证码发送发送成功的提示
        'voice_send_failure' => "语音验证码请求失败，请稍后重试",

        // 验证码短信发送成功的提示
        'sms_send_success' => "短信验证码发送成功，请注意查收",

        // 语音验证码发送发送成功的提示
        'voice_send_success' => "语音验证码发送成功，请注意接听",
    ],

    /*
     * -----------------------------------
     * 验证码短信相关配置
     * -----------------------------------
     * verifySmsContent: 验证码短信通用内容
     * codeLength: 验证码长度
     * codeValidTime: 验证码有效时间长度，单位为分钟(minutes)
     */
    'verifySmsContent' => "【东方丽人】亲爱的用户，您的验证码是%s。有效期为半小时，请尽快验证",

    'codeLength' => 5,

    'codeValidTime' => 30,

    'notifyNewOrder' => '【东方丽人】你有新的订单，请到后台查看',
    'notifyAgentApprove' => '【东方丽人】你的代理申请已通过,请登录后台查看',
    'notifyAgentReject' => '【东方丽人】你的代理申请已被驳回, 请登录后台查看',

    /*
     * -----------------------------------
     * Storage system
     * -----------------------------------
     * storePrefixKey: 存储key的prefix
     * storage: 存储方式
     */
    'storage' => App\Repositories\Auth\SmsStorage::class,

    'storePrefixKey' => 'laravel_sms_info',

    /*
     * -----------------------------------
     * queue job
     * -----------------------------------
     */
    'queueJob' => 'App\Jobs\SendReminderSms',
];

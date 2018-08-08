<?php

return [

    /*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    /*
	 * Socialite Credentials
	 * Redirect URL's need to be the same as specified on each network you set up this application on
	 * as well as conform to the route:
	 * http://localhost/public/auth/login/SERVICE
	 * Where service can github, facebook, twitter, or google
	 */

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],

    'weixin' => [
        'client_id' => env('WECHAT_APPID'),
        'client_secret' => env('WECHAT_APPSECRET'),
        'redirect' => env('WECHAT_REDIRECT_URL'),
        'auth_base_uri' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
        'redirect_urls' => [
            'client' => env('WEIXIN_REDIRECT_URL_CLIENT'),
            'station' => env('WEIXIN_REDIRECT_URL_STATION'),
            'store' => env('WEIXIN_REDIRECT_URL_STORE'),
            'staff' => env('WEIXIN_REDIRECT_URL_STAFF'),
            'admin' => env('WEIXIN_REDIRECT_URL_ADMIN'),
            'test' => env('WEIXIN_REDIRECT_URL_TEST'),
            'deliver' => env('WEIXIN_REDIRECT_URL_DELIVER'),
            'gift' => env('WEIXIN_REDIRECT_URL_GIFT'),
            'department' => env('WEIXIN_REDIRECT_URL_DEPARTMENT'),
            'linkWeChatProducts'=>env('WEIXIN_REDIRECT_URL_PRODUCT'),
        ]
    ],

    'pingxx' => [
        'app_id' => env('PINGXX_APP_ID'),
        'api_key' => env(env('PINGXX_ACCOUNT_ENV', 'TEST') . '_PINGXX_API_KEY'),
        'live' => env('PINGXX_LIVE_MODE', false),
        'mobile_success' => env('PAYMENT_MOBILE_SUCCESS_URL'),
        'mobile_cancel' => env('PAYMENT_MOBILE_CANCEL_URL'),
        'pc_success' => env('PAYMENT_PC_SUCCESS_URL'),
        'pc_cancel' => env('PAYMENT_PC_CANCEL_URL'),
        'pub_key_path' => app_path('Repositories/Pay/Pingxx') . '/pingpp_rsa_public_key.pem',
    ],

    'search' => [
        'root' => env('SEARCH_ROOT_PATH'),
        'name' => env('SEARCH_APP_NAME')
    ],

    'subscribe' => [
        'pause_days' => env('SUBSCRIBE_PAUSE_DAYS', 2),
    ],

    'notify' => [
        'sms' => env('NOTIFY_BY_SMS', 0),
        'weixin' => env('NOTIFY_BY_WEIXIN', 1)
    ],
    'aliyun' => [
        'accessKeyId' => env('OSS_ACCESS_ID','LTAImJUPg2oIJyYn'),
        'accessKeySecret' => env('OSS_ACCESS_KEY','ARSfYUk6eW9ksfU5ogTjgACv7Fq5g0'),
        'oss_endpoint' => env('OSS_ENDPOINT','leshangnetwork.oss-cn-shenzhen.aliyuncs.com'),
        'oss_bucket' => env('OSS_TEST_BUCKET','yantang'),
    ],

    'localStorageFile' => [
        'path' => env('LOCAL_STORAGE_PATH','public/SignFile/'),
        'SignRule' => env('LOCAL_STORAGE_RULE','SignRule.json')
    ],
];

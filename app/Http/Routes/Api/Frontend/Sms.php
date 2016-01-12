<?php

$api->get('sms/info/{uuid?}', 'SmsController@getInfo');

$api->post('sms/verify-code', 'SmsController@verifyCode');
$api->post('sms/send-code', 'SmsController@postSendCode');

$api->post('sms/voice-verify', 'SmsController@postVoiceVerify');

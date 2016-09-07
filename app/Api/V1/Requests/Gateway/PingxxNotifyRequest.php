<?php

namespace App\Api\V1\Requests\Gateway;

use App\Http\Requests\Request;

class PingxxNotifyRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (app()->environment() == 'testing') {
            return true;
        }
        
        $signature = $this->header('X-Pingplusplus-Signature');
        return $this->verify_signature(file_get_contents('php://input'), $signature) === 1;
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

    protected function verify_signature($raw_data, $signature)
    {
        $pub_key_contents = file_get_contents(config('services.pingxx.pub_key_path'));

        // php 5.4.8 以上，第四个参数可用常量 OPENSSL_ALGO_SHA256
        return openssl_verify($raw_data, base64_decode($signature), $pub_key_contents, OPENSSL_ALGO_SHA256);
    }

}



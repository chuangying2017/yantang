<?php namespace App\Http\Requests;

use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class Request
 * @package App\Http\Requests
 */
abstract class Request extends FormRequest {


    public function forbiddenResponse()
    {
        throw new AccessDeniedHttpException();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('请求数据格式错误', $validator->errors());
    }


}


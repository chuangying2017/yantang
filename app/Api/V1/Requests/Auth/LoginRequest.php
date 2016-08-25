<?php namespace App\Api\V1\Requests\Auth;

use App\Api\V1\Requests\Request;


/**
 * Class LoginRequest
 * @package App\Http\Requests\Frontend\Access
 */
class LoginRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'phone'    => 'required|zh_mobile',
            'password' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'password' => '密码'
        ];
    }
}

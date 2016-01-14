<?php namespace App\Http\Requests\Frontend\Access;

use App\Http\Requests\Request;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\Frontend\Access
 */
class RegisterRequest extends Request {

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

        $token = $this->input('token', null) ?: $this->input('uuid', null);

        //验证手机验证码

        return [
            'name'     => 'required|max:255',
            'phone'    => 'required|zh_mobile|unique:users,phone|confirm_mobile_not_change:' . $token,
            'password' => 'required|confirmed|min:6',
        ];
    }
}

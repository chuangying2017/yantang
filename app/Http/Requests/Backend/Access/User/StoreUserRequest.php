<?php namespace App\Http\Requests\Backend\Access\User;

use App\Http\Requests\Request;
use App\Repositories\Backend\AccessProtocol;

/**
 * Class StoreUserRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class StoreUserRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasRole(AccessProtocol::ROLE_OF_SUPERVISOR);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'					=>  'required',
            'phone'					=>	'required|unique:users',
            'password'				=>	'required|alpha_num|min:6|confirmed',
            'password_confirmation'	=>	'required|alpha_num|min:6',
        ];
    }
}

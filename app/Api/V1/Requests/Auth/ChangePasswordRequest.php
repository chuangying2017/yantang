<?php namespace App\Api\V1\Requests\Auth;

use App\Api\V1\Requests\Request;

/**
 * Class ChangePasswordRequest
 * @package App\Api\V1\Requests\Frontend\Access
 */
class ChangePasswordRequest extends Request {

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
			'old_password'	=>  'required',
			'password'		=>	'required|min:6|confirmed',
		];
	}
}

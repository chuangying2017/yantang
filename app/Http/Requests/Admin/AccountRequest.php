<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

use Auth;

class AccountRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_admin = Auth::get();
        $route_name = $this->route()->getName();
        if ($auth_admin->role == 'super') {
            return true;
        } else if ($route_name == 'admin.account.edit' || $route_name == 'admin.account.update') {

            if ($auth_admin->id == $this->route()->parameter('account')) {
                return true;
            }

        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'username' => 'required|unique:admin',
                    'password' => 'required|confirmed',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'username' => 'required|unique:admin,username,' . $this->route()->parameter('account'),
                    'password' => 'required|confirmed',
                ];
            }
            default:
                break;
        }

    }
}

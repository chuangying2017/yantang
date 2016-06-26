<?php

namespace App\Api\V1\Requests\Station;

use App\Http\Requests\Request;
use App\Repositories\Station\Staff\StaffRepositoryContract;

class BindStaffRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(StaffRepositoryContract $staffRepo)
    {
        return $staffRepo->getBindToken($this->route()->getParameter('staff_id')) == $this->input('bind_token');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bind_token' => 'required'
        ];
    }
}

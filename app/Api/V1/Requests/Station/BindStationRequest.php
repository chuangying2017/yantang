<?php

namespace App\Api\V1\Requests\Station;

use App\Http\Requests\Request;

class BindStationRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return check_bind_token($this->route()->getParameter('station_id'), $this->input('bind_token'));
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

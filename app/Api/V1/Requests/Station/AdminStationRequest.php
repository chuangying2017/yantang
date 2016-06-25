<?php namespace App\Api\V1\Requests\Station;

use App\Api\V1\Requests\Request;


/**
 * Class RegisterRequest
 * @package App\Api\V1\Requests\Frontend\Access
 */
class AdminStationRequest extends Request
{

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
            'name' => 'required',
            'director' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ];
    }
}

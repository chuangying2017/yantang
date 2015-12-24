<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Services\Home\BannerService;

class BannerRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #todo auth
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //
        ];

        if ($this->isMethod('POST') || $this->isMethod('PUT')) {
            $rules = [
                'cover_image' => 'required',
                'type'        => 'required|in:' . BannerService::TYPE_OF_SLIDER . ',' . BannerService::TYPE_OF_GRID
            ];
        }

        return $rules;
    }
}

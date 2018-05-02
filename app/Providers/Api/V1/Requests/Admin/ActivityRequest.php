<?php namespace App\Api\V1\Requests\Admin;

use App\Http\Requests\Request;


class ActivityRequest extends Request {

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
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'coupons' => 'required',
            'background_color' => 'required',
            'cover_image' => 'required',
            'share_image' => 'required',
            'share_friend_title' => 'required',
            'share_desc' => 'required',
            'share_moment_title' => 'required',
            'can_share' => 'required',
        ];
    }
}

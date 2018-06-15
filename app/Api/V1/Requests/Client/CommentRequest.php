<?php

namespace App\Api\V1\Requests\Client;

use App\Http\Requests\Request;

class CommentRequest extends Request
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
            'comment_id'=>'required|numeric|max:10',
            //
        ];
    }

    public function messages()
    {
        return [
            'comment_id.required' => 'commentID not null',
            'comment_id.numeric'  => 'must is number',
            'comment_id.max'      => 'maximum then bit',
        ]; // TODO: Change the autogenerated stub
    }
}
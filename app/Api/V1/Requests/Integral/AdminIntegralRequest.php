<?php

namespace App\Api\V1\Requests\Integral;

use App\Http\Requests\Request;

class AdminIntegralRequest extends Request
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
            //
            'sort_type'     => 'required|numeric|max:10',
            'title'         => 'required|max:30|min:6',
            'cover_image'   => 'required|max:200|min:10',
            'status'        => 'nullable|max:10',
        ];
    }

    public function messages()
    {
        return [
            'sort_type.required'        =>  '排序类型不能为空',
            'sort_type.numeric'         =>  '必须是数值',
            'sort_type.max'             =>  '数值最大为10',
            'title.required'            =>  '分类名称必须',
            'title.min'                 =>  '最小的长度为6',
            'cover_image.required'      =>  '分类图片不能为空',
        ];
    }
}

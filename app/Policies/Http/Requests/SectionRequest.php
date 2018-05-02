<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SectionRequest extends Request {

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

        if ($this->isMethod('PUT') || $this->isMethod('POST')) {
            $rules = [
                'title' => 'required'
            ];
        }

        if ($this->route()->getName() == 'sections.bind.products') {
            $rules = [];
            foreach ($this->request->get('products') as $key => $product) {
                $rules[ 'products.' . $key . '.title' ] = 'required';
                $rules[ 'products.' . $key . '.url' ] = 'required';
            }
        }

        return $rules;
    }
}

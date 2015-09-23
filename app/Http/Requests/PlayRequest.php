<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Crypt;

class PlayRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

	    // if($this->route()->getName() == 'play.store') {
		   //  if ( md5($this->getClientIp() . csrf_token()) != session('play_token') )
		   //  {
			  //   return false;
		   //  }
	    // }

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
        ];
    }
}

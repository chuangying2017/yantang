<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;
use Auth;

class AdminBaseController extends Controller{


	public function __construct()
	{
		$this->auth = Auth::admin();
		view()->share('auth_admin', $this->auth->get());
	}




}

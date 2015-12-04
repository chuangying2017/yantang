<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;

class AuthUserController extends Controller {

	protected $auth;

	public function __construct()
	{
		$this->auth = Auth::user()->check() ? Auth::user() : '';
	}

}

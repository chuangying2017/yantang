<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\CreditCard;
use App\CreditCardCoupon;
use App\Exchange;

class AdminController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = [];

		return view('admin.index', compact('data'));
	}


}

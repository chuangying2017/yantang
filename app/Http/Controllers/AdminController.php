<?php namespace App\Http\Controllers;

use App\Http\Controllers\Backend\Api\AdminBaseController;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;


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

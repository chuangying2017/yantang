<?php

namespace App\Http\Controllers\Api\Backend;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\AccountRequest as Request;

use App\Models\Admin;

use Hash;


class AccountController extends BaseController {


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 * GET /adminaccount
	 *
	 * @return
	 */
	public function index(Request $request)
	{
		$admins = Admin::where('role', '!=', 'super')->get();
		return view('admin.account.index', compact('admins'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /adminaccount/create
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('admin.account.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /adminaccount
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$input = $request->all();
		$admin = new Admin();
		$admin->username = $input['username'];
		$admin->password = Bcrypt($input['password']);
		$admin->role = 'normal';
		$admin->save();

		return redirect()->action('AccountController@index');
	}


	public function edit(Request $request, $id)
	{
		$admin = Admin::findOrFail($id);
		return view('admin.account.edit', compact('admin'));
	}


	public function update(Request $request, $id)
	{

		$input = $request->all();

		$username = $input['username'];
		$password = $input['password'];

		$admin = Admin::findOrFail($id);

		$auth_admin = $this->auth->get();
		if( $auth_admin->role == 'super' || Hash::check($input['origin_password'],  $admin->password)) {

			$admin->username = $username;
			$admin->password = bcrypt($password);
			$admin->save();

			if($auth_admin->id == $id) {
				return redirect()->route('admin.logout');
			}

			return redirect()->back()->with('message', '更新成功')->with('type', 'success');
		} else {
			return redirect()->back()->with('message', '密码错误')->with('type', 'danger');
		}

	}

}

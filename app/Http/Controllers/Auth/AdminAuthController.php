<?php namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;


class AdminAuthController extends Controller {

    private $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     * @param  \Illuminate\Contracts\Auth\Registrar $registrar
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->auth = $auth;
    }

    public function getLogin()
    {
        if ($this->auth->check()) {
            return redirect()->route('backend.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/create
     *
     * @return Response
     */
    public function postLogin(Request $request)
    {

        $input = $request->all();

        if ($this->auth->attempt(array_only($input, ['username', 'password']))) {
            return redirect()->route('backend.dashboard');
        }

        return redirect()->route('admin.login')->withMessage('账号或密码错误')->withType('danger');
    }

    /**
     * Display the specified resource.
     * GET /admin/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect()->route('admin.login');
    }


}

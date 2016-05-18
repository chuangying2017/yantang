<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Controllers\Backend\Api\BaseController;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Client\Client;

//use App\Http\Requests\Admin\ClientRequest as Request;



class AdminClientController extends BaseController
{

		public function __construct()
		{
			parent::__construct();
		}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
			return redirect()->route('admin.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
	    return View('admin.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
	    $input = $request->all();

	    $client = new Client();
	    $client->name = $input['name'];
	    $client->phone = $input['phone'];
	    $client->address = $input['address'];
	    $client->save();

        $messages = ['创建成功，请继续创建商家管理员账号'];

	    return redirect()->action('AccountController@create', ['client_id' => $client->id])->with('messages', $messages)->with('type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
	    $client = Client::findOrFail($id);
	    return view('admin.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $client = Client::find($id);
        $client->name = $input['name'];
        $client->phone = $input['phone'];
        $client->address = $input['address'];
        $client->save();

        return redirect()->action('AccountController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

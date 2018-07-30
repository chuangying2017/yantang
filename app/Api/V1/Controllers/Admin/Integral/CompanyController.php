<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Repositories\Integral\Supervisor\Supervisor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    protected $company;

    public function __construct(Supervisor $supervisor)
    {
        $this->company=$supervisor;
    }

    /**
     * Display a listing of the resource.
     * @api {get} /admin/integral/company 查询快递公司
     *
     * @apiSuccess {array} 返回状态200|多维数组或者返回空数组
     *
     */
    public function index()
    {
        $fetch_all = $this->company->get_all();

        return $this->response->array($fetch_all);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @api {post} /admin/integral/company 添加快递公司
     * @apiName GetCompany
     * @apiGroup Integral
     * @apiParam  {string} name 公司名称
     * @apiParam {string} detail 公司描述可以为空
     *
     * @apiSuccess {status} status 成功返回201状态
     */
    public function store(Request $request)
    {
        $this->company->create($request->all());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

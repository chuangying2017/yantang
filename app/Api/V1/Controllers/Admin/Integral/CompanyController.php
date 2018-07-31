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
     * @api {get} /admin/integral/company/{id} 查快递公司 unique ID
     * @apiName GetCompanyOneData
     * @apiGroup Integral
     *
     * @apiSuccess {status} status 成功返回一个数组|返回空数组
     * @apiError CompanyNotFound The <code>id</code> of the company was not found
     *
     *
     */
    public function show($id)
    {
        $company_data = $this->company->find((integer)$id);

        return $this->response->array($company_data);
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
     * @api {get} /admin/integral/company/{id} 查询快递 unique ID
     * @apiName GetSelectCompany
     * @apiGroup Integral
     *
     * @apiParam {string} name 快递名称
     * @apiParam {string} status 更改状态active启用|inactivity禁用
     * @apiParam {string} detail 详情可为空
     * @apiParam {string} type 默认类型是express表示快递公司
     *
     * @apiSuccess {code} status 成功返回201状态码
     * @apiError CompanyInsertError The insert data exception could not add
     *
     */
    public function update(Request $request, $id)
    {
        $this->company->update($id, $request->all());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->company->delete($id);

        return $this->response->noContent()->statusCode(201);
    }
}

<?php
/**
 * @api {get} /admin/integral/freedomThe/shippingManagement 发货管理
 * @apiName GetManager
 * @apiGroup Integral
 *
 * @apiParam {string} keywords  关键字
 * @apiParam {date} start_time  开始时间
 * @apiParam {date} end_time    结束时间
 * @apiParam {string} export    如果值等于all导出
 *
 * @apiSuccess {array} arrayName 请求成功就是一个数据集数组或者空的数组.
 */

/**
 * @api {get} /admin/integral/freedomThe/{id}/shippingOrderDetail 发货详情
 * @apiName GetIntegral
 * @apiGroup Integral
 *
 *
 * @apiSuccess {array} arrayName 请求成功就是一个数据集数组或者空的数组.
 */

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

/**
 * Display a listing of the resource.
 * @api {get} /admin/integral/company 查询快递公司
 * @apiName GetCompanyData
 * @apiGroup Integral
 *
 * @apiSuccess {array} status 返回状态200|多维数组或者返回空数组
 *
 */

/**
 * Display the specified resource.
 * @api {get} /admin/integral/company/{id} 获取快递公司
 * @apiName getCompanyOneData
 * @apiGroup Integral
 *
 * @apiSuccess {status} status 成功返回一个数组|返回空数组
 * @apiError CompanyNotFound The <code>id</code> of the company was not found
 *
 *
 */

/**
 * Update the specified resource in storage.
 *
 * @api {get} /admin/integral/company/{id} 快递编辑 unique ID
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

/**
 * @api {post} /admin/integral/freedomThe/{order_id}/convert_confirm 确定兑换
 * @apiName GetExpressConfirm
 * @apiGroup Integral
 * @apiParam {string} express 快递公司名称不能为空
 * @apiParam {Number} expressOrder 快递单号数字或字符串不能为空
 * @apiSuccess {statusCode} status 成功返回成功201
 * @apiError InternalError program error not update 500
 */

/**
 * @api {get} /integral/showMemberOrder 参与记录
 * @apiName GetFrontDeskMeeting
 * @apiGroup FrontDesk
 *
 * @apiSuccess {statusCode} status 请求成功返回多维数组 | 或者返回空的数组
 * @apiError OrderDataNotFound this is order data not found 404
 */
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
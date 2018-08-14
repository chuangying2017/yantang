<?php

/**
 * Display the specified resource.
 * @api {get} /integral/fetchIntegral/{id} 获取id积分卡
 * @apiName GetIntegralCardFrontDesk
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功返回对象类型
 * @apiSuccess {string} object.name 名称
 * @apiSuccess {string} object.give 赠送积分
 * @apiSuccess {date} object.start_time 开始时间
 * @apiSuccess {date} object.end_time  结束时间
 * @apiSuccess {string} object.status  show上架 | hide隐藏
 * @apiSuccess {string} object.type  limits限制发布数量 \ loose无限制
 * @apiSuccess {url} object.cover_image 展示图片
 */

/**
 * @api {get} /admin/integral/validityGet 积分有效期Get
 * @apiName GetIntegralValidity
 * @apiGroup Integral
 * @apiSuccess {object} object 返回对象
 * @apiSuccess {array} object.setting 返回设置数组
 * @apiSuccess {array} object.protocol 返回协议数组
 */

/**
 * @api {put} /admin/integral/validity/{id}/Update 积分有效期更新
 * @apiName GetIntegralValidityUpdate
 * @apiGroup Integral
 * @apiParam {object} setting 对象类型setting:{year:1}
 * @apiParam {numeric} setting.year 数值类型
 * @apiParam {object} where 对象where:{type:3}更新协议条件
 * @apiParam {numeric} where.type 数值类型
 * @apiParam {object} protocol 协议对象
 * @apiParam {string} protocol.protocol_content 字符类型text方式
 * @apiSuccess {statusCode} statusCode Successfully status code 201
 * @apiError InternalError possible internal code error
 * @apiDescription 数值id对应setting里的id
 */
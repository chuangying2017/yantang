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
 * @api {get} /admin/integral/validity 积分有效期Get
 * @apiName GetIntegralValidity
 * @apiGroup Integral
 * @apiSuccess {object} object 返回对象
 */

/**
 * @api {put} /admin/integral/validity/{id}/Update 积分有效期更新
 * @apiName GetIntegralValidityUpdate
 * @apiGroup Integral
 * @apiParam {numeric} year 数值类型
 * @apiSuccess {statusCode} statusCode Successfully status code 201
 * @apiError InternalError possible internal code error
 */
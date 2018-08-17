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

/**
 * Update the specified resource in storage.
 *
 * @api {put} /integral/fetchIntegral/{id} 领取积分卡
 * @apiName GetIntegralCard
 * @apiGroup FrontDesk
 * @apiSuccess {statusCode} statusCode 成功返回201状态
 * @apiError InternalError 内部代码有误 返回错误信息500
 */

/**
 * @api {get} /integral/integralCard 兑换卷列表
 * @apiName GetIntegralCardList
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 返回对象
 * @apiSuccess {string} object.name 标题
 * @apiSuccess {string} object.description 描述
 * @apiSuccess {numeric} object.cost_integral 兑换消耗积分
 * @apiSuccess {float} object.couponAmount 优惠金额
 * @apiSuccess {url} object.cover_image 图片链接
 * @apiSuccess {numeric} object.delayed 兑换后小时为单位
 * @apiError InternalError possible internal error code 500 reject msg
 */

/**
 * @api {get} /integral/integralCard/{id} 兑换卷获取id
 * @apiName GetIntegralCardFind
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 返回对象
 * @apiSuccess {object} object.name 标题
 * @apiSuccess {string} object.description 描述
 * @apiSuccess {numeric} object.cost_integral 兑换消耗积分
 * @apiSuccess {float} object.couponAmount 优惠金额
 * @apiSuccess {url} object.cover_image 图片链接
 * @apiSuccess {numeric} object.delayed 兑换后小时为单位
 */

/**
 * @api {put} /integral/integralFetchCoupon/{convertId} 积分兑换卷
 * @apiName GetIntegralConvert
 * @apiGroup FrontDesk
 * @apiSuccess {statusCode} status 成功返回状态码200
 * @apiError InternalError return status code 500 and error messages
 */

/**
 * @api {get} /integral/integralProtocol 获取协议
 * @apiName GetIntegralProtocol
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功返回对象
 */

/**
 * @api {get} /integral/integralRecord?page=1 积分明细
 * @apiName GetIntegralRecord
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功返回对象或空数组
 * @apiSuccess {string} object.name 名称
 * @apiSuccess {date} object,created_at 创建时间
 * @apiSuccess {string} object.integral 积分
 * @apiDescription page如果不传默认是第一页每页20条
 */
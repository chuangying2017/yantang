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
 * @apiSuccess {string} object.status red可以买gray不可购买
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

/**
 * @api {get} /integral/integralSignGet 点击签到
 * @apiName GetIntegralSignGet
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功返回对象{status:1,message:successfully}
 * @apiSuccess {object} object 失败返回对象{status:2,message:errorMessage}
 */

/**
 * Display a listing of the resource.
 *
 * @api {get} /integral/integralSignMonthAll 获取当月签到
 * @apiName GetIntegralSignMonthAllData
 * @apiGroup FrontDesk
 * @apiParam {date} date 日期类型必须是2018-02-09 格式
 * @apiSuccess {object} object 成功返回对象类型  | 或者返回空数组
 * @apiSuccess {numeric} object.total 当月总签到
 * @apiSuccess {numeric} object.continuousSign 连续签到次数
 * @apiSuccess {object}  object.signDay 多维对象  签到天数
 * @apiSuccess {numeric} object.signDay.day 对应的当前的号数
 */

/**
 * @api {post} /admin/integral/updateIntegralRecord 后台加减积分
 * @apiName GetIntegralOperation
 * @apiGroup Integral
 * @apiParam {numeric} user_id 用户id不能为空并且是对象形式user_id:{94556,94566,94666}
 * @apiParam {numeric} increase 增加积分不能为空|或者decrease 不能为空
 * @apiParam {numeric} decrease 减少积分不能为空|或者increase 不能为空
 * @apiSuccess {statusCode} status success return 201 code
 * @apiError InternalError error is possible internal problem
 * @apiDescription increase and decrease 只能提交其中一个字段
 */

/**
 * @api {get} /integral/integralGetRule 签到规则
 * @apiName GetIntegralRuleFrontDesk
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功返回数据对象
 */

/**
 * @api {get} /integral/integralSignRepair/{day} 补签
 * @apiName GetIntegralRepair
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 返回数据对象{status:1成功}{status:2失败}
 */

/**
 * @api {get} /integral/integralSignContinue/{day} 获取连续签到积分
 * @apiName GetIntegralContinueSign
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功或失败返回对象{status:1成功}|{status:2失败}
 */

/**
 * @api {get} /integral/UserPhotoIntegral 获取用户头像积分
 * @apiName GetIntegralPhoto
 * @apiGroup FrontDesk
 * @apiSuccess {object} object 成功返回用户信息
 */

/**
 * @api {get} /admin/integral/freedomThe/shippingStatus/{order_status?} 获取待发货
 * @apiName GetIntegralOrderStatus
 * @apiGroup Integral
 * @apiSuccess {object} object 成功返回对象{number:integer}
 *
 */
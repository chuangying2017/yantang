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
 * @apiParam {string} status DropShip待发货Delivered待收货confirm已完成
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
 * @api {put} /admin/integral/company/{id} 快递编辑 unique ID
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

/**
 * @api {post} /admin/integral/freedomThe/card_manager 添加积分卡
 * @apiName GetCardManager
 * @apiGroup Integral
 * @apiParam {string} name 名称|字符串类型不能为空
 * @apiParam {float} give 赠送积分|浮点类型不能为空
 * @apiParam {date} start_time 开始时间2017-01-01 13:00:01 不能为空必须大于等于结束时间
 * @apiParam {date} end_time 结束时间2018-01-02 14:00:01 不能为空
 * @apiParam {string} status 不能为空 show上架 | hide隐藏
 * @apiParam {string} type  不能为空 limits限制发布数量 \ loose无限制
 * @apiParam {url} cover_image  图片地址 不能为空
 * @apiParam {number} issue_num 发布数量数值类型
 * @apiParam {integer} draw_num 限制会员领取数量
 * @apiParam {integer} mode 设置0不限制 1限制新用户
 *
 * @apiSuccess {statusCode} status 成功后返回200状态code
 * @apiError InternalError parameters type have Wrong
 *
 *
 * @apiDescription 每个地段必须按照规定的参数类型，传值否者无法生效！
 *
 */

/**
 * @api {get} /admin/integral/freedomThe/integralCardShow 获取积分卡
 *
 * @apiName GetIntegralCardShowAll
 * @apiGroup Integral
 * @apiSuccess {statusCode} status 成功返回200并与多维数组|空数组
 * @apiSuccess {numeric} card_id 数值
 * @apiSuccess {string} name 名称
 * @apiSuccess {numeric} give 领取积分
 * @apiSuccess {status} status 积分卡状态show展示hide隐藏
 * @apiSuccess {string} type limits限制 | loose 宽松
 * @apiSuccess {numeric} mode 0全部会员可领取1新会员不可领取
 * @apiSuccess {url} cover_image 展示图片链接地址
 * @apiSuccess {integer} issue_num 发布数量
 * @apiSuccess {integer} draw_num 会员可领取数量
 * @apiSuccess {date} start_time 开始时间
 * @apiSuccess {date} end_time 结束时间
 * @apiSuccess {integer} remain 剩余可领取数量
 * @apiSuccess {integer} get_member 会员已领取数量
 * @apiError DataNotFound Maybe there's no data in the table
 *
 */

/**
 * @api {get} /admin/integral/freedomThe/IntegralCardFind/{id} 获取id积分卡
 * @apiName GetIntegralCardId
 * @apiGroup Integral
 *
 * @apiSuccess {statusCode} status 返回数组
 * @apiError CardNotFound error message or where no be verify
 * @apiDescription 返回数据或者一直报错 id必须是数值
 */

/**
 * @api {post} /admin/integral/freedomThe/{card_id}/card_update 积分卡id编辑
 * @apiName GetCardUpdate
 * @apiGroup Integral
 * @apiParam {string} name 名称|字符串类型不能为空
 * @apiParam {float} give 赠送积分|浮点类型不能为空
 * @apiParam {date} start_time 开始时间2017-01-01 13:00:01 不能为空必须大于等于结束时间
 * @apiParam {date} end_time 结束时间2018-01-02 14:00:01 不能为空
 * @apiParam {string} status 不能为空 show上架 | hide隐藏
 * @apiParam {string} type  不能为空 limits限制发布数量 \ loose无限制
 * @apiParam {url} cover_image  图片地址 能为空
 * @apiParam {number} issue_num 发布数量数值类型
 * @apiParam {integer} draw_num 限制会员领取数量
 *
 * @apiSuccess {statusCode} status 成功后返回200状态code
 * @apiError InternalError parameters type have Wrong
 *
 *
 * @apiDescription 每个地段必须按照规定的参数类型，传值否者无法生效！
 *
 */

/**
 * @api {delete} /admin/integral/freedomThe/{card_id}/card_delete 删除积分卡
 * @apiName GetIntegralCardDel
 * @apiGroup Integral
 *
 * @apiSuccess {statusCode} status 成功返回201状态码
 * @apiError InternalError Parameters maybe is error or other exception
 * @apiDescription 请求方式必须与描述得一致 根据url的card_id必须是数值
 */

/**
 * Update the specified resource in storage.
 *
 * @api {post} /admin/integral/exchangeThe 积分兑卷添加
 * @apiParam {string} name 最低长度1位
 * @apiParam {string} description 描述存在验证
 * @apiParam {numeric} cost_integral 数值类型 消耗积分
 * @apiParam {numeric} promotions_id 数值类型 优惠券id
 * @apiParam {date} valid_time 开始时间2018-08-03 13:08:08
 * @apiParam {date} deadline_time 结束时间2018-08-04 13:08:08必须大于开始时间
 * @apiParam {integer} status 0下架1上架
 * @apiParam {integral} issue_num 发布数量默认整形不能小于0
 * @apiParam {integer} draw_num 已经领取数量
 * @apiParam {integer} remain_num 剩余数量
 * @apiParam {integral} delayed 领取后的延时 时长单位小时
 * @apiParam {url}  cover_image 上传图片地址可为空
 * @apiParam {integer} member_type 限制新旧会员领取0全部可领取1新会员不可领取
 * @apiParam {integer} limit_num 限制会员兑换数量
 *
 * @apiName GetExchangeAddition
 * @apiGroup Integral
 *
 * @apiSuccess {statusCode} status 成功返回201状态
 * @apiError InternalError possible problem appear in background program code
 *
 * @apiDescription 请严格按照上面描述传参 否者可能内部出错无法插入
 */

/**
 * Update the specified resource in storage.
 *
 * @api {put} /admin/integral/exchangeThe/{id} 积分兑卷更新
 * @apiParam {string} name 最低长度1位
 * @apiParam {string} description 描述存在验证
 * @apiParam {numeric} cost_integral 数值类型 消耗积分
 * @apiParam {numeric} promotions_id 数值类型 优惠券id
 * @apiParam {date} valid_time 开始时间2018-08-03 13:08:08
 * @apiParam {date} deadline_time 结束时间2018-08-04 13:08:08必须大于开始时间
 * @apiParam {integer} status 0下架1上架
 * @apiParam {integral} issue_num 发布数量默认整形不能小于0
 * @apiParam {integer} draw_num 已经领取数量
 * @apiParam {integer} remain_num 剩余数量
 * @apiParam {integral} delayed 领取后的延时 时长单位小时
 * @apiParam {url}  cover_image 上传图片地址可为空
 * @apiParam {integer} member_type 限制新旧会员领取0全部可领取1新会员不可领取
 * @apiParam {integer} limit_num 限制会员兑换数量
 *
 * @apiName GetExchange
 * @apiGroup Integral
 *
 * @apiSuccess {statusCode} status 成功返回201状态
 * @apiError InternalError possible problem appear in background program code
 *
 * @apiDescription 请严格按照上面描述传参 否者可能内部出错无法插入
 */

/**
 * @api {delete} /admin/integral/exchangeThe/{id} 积分兑卷删除
 * @apiName GetExchangeDelete
 * @apiGroup Integral
 *
 * @apiSuccess {statusCode} status deleteSuccessfully return status code 201
 * @apiError internalError internal fatal error possible problem code id not found
 *
 * @apiDescription 注意地址上的id必须是数值
 */

/**
 * Remove the specified resource from storage.
 *
 * @api {delete} /admin/integral/company/{id} 删除快递
 * @apiName GetDeleteCompany
 * @apiGroup Integral
 *
 * @apiSuccess {statusCode} status successfully of return status code 201
 * @apiError IdNotFound possible id is not found
 */

/**
 * Display the specified resource.
 *
 * @api {get} /admin/integral/exchangeThe/{id} 获取id兑换
 * @apiName GetExchangeShow
 * @apiGroup Integral
 * @apiSuccess {array} array request successfully return array OR empty array
 * @apiError IdNotFound possible internal code fatal error
 *
 *
 */

/**
 * Display a listing of the resource.
 *
 * @api {get} /admin/integral/exchangeThe 获取全部兑换
 *
 * @apiName GetExchangeGetAll
 * @apiGroup Integral
 * @apiSuccess {array} array successfully of return many array | or null array
 * @apiSuccess {string} array.description 描述
 * @apiSuccess {string} array.cost_integral 消耗积分
 * @apiSuccess {string} array.promotions_id 优惠券id
 * @apiSuccess {date} array.valid_time 有效时间
 * @apiSuccess {date} array.deadline_time 结束时间
 * @apiSuccess {string} array.status 上下架
 * @apiSuccess {string} array.issue_num 发布数量
 * @apiSuccess {string} array.draw_num 领取数量
 * @apiSuccess {string} array.remain_num 剩余数量
 * @apiSuccess {numeric} array.delayed 延时时间为准小时
 * @apiSuccess {integer} array.member_type 限制新旧会员领取0全部可领取1新会员不可领取
 * @apiSuccess {integer} array.limit_num 限制会员兑换数量
 * @apiSuccess {integer} array.used_num 使用数量
 * @apiDescription 本接口返回一个分页状态
 */

/**
 * @api {get} /admin/integral/freedomThe/sign/signGet 获取签到规则
 * @apiName GetIntegralSignRule
 * @apiGroup Integral
 * @apiSuccess {array} array 成功返回数组或空数组
 * @apiDescription 必须安装请求方式去获取无需带任何参数
 */

/**
 * @api {put} /admin/integral/freedom/sign/signUpdate edit签到规则
 * @apiName GetIntegralSignUpdate
 * @apiGroup Integral
 * @apiParam {numeric} status 开启签到0关闭1开启 not null
 * @apiParam {numeric} retroactive 默认开启补签1开启0关闭 not null
 * @apiParam {string} state 签到规则说明 can null
 * @apiParam {array} extend_rule 用这种方式{"sex": "girl"}提交
 * @apiParam {numeric} extend_rule.compensateIntegral 扣除补签积分
 * @apiParam {string} extend_rule.firstRewards 首次奖励{status:1开启0关闭,rewards:100,everyday:10}
 * @apiParam {string} extend_rule.continuousOne 连续签到{status:1开启0关闭,days:10,rewards:10}
 * @apiParam {string} extend_rule.continuousTwo 连续签到{status:1开启0关闭,days:20,rewards:20}
 * @apiParam {string} extend_rule.continuousThree 连续签到{status:1开启0关闭,days:30,rewards:30}
 * @apiParam {string} extend_rule.continuousSum 总签奖励{status:1开启0关闭,days:50,rewards:100}
 * @apiParam {string} extend_rule.autorelease 特殊奖励{status:1开启0关闭,title:年庆,rewards:1000,date:2018-05-06}
 * @apiSuccess {statusCode} statusCode successfully condition return 201 code or return 500
 * @apiDescription if successfully return 201 No person is fail fatal error internal problem
 */

/**
 * @api {put} /admin/integral/category/{id} 更新分类
 * @apiName GetIntegralCategoryUpdate
 * @apiGroup Integral
 * @apiParam {string} title 标题不能为空 not null
 * @apiParam {numeric} sort_type 排序类型int not null
 * @apiParam {string} status show展示hide隐藏 not null
 * @apiParam {string} cover_image 展示图链接 can null
 *
 * @apiSuccess {array} status 返回对象
 * @apiSuccess {string} status.title 标题
 * @apiSuccess {numeric} status.sort_type 排序类型
 * @apiSuccess {string} status.cover_image 图链接
 *
 */
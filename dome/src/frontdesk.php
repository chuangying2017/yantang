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
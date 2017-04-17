<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 8/5/16
 * Time: 11:26 AM
 */

namespace App\Services\Promotion\Rule\Qualification;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class QualifyChecker {

    /**
     * @var Qualification
     */
    protected $checker;

    /**
     * @param $type
     * @return $this
     * @throws \Exception
     */
    public function setQualifyType($type)
    {
        if (is_null(PromotionProtocol::getQualifyType($type))) {
            throw new \Exception('错误的优惠规则资格类型');
        }

        $handler = [
            PromotionProtocol::QUALI_TYPE_OF_ALL => AllUserQualification::class,
            PromotionProtocol::QUALI_TYPE_OF_LEVEL => GroupUser::class,
            PromotionProtocol::QUALI_TYPE_OF_ROLE => RoleUsers::class,
            PromotionProtocol::QUALI_TYPE_OF_USER => SpecifyUsers::class,
            PromotionProtocol::QUALI_TYPE_OF_GROUP => GroupUser::class,
            PromotionProtocol::QUALI_TYPE_OF_FIRST_PRE_ORDER => FirstPaidSubscribeOrder::class,
            PromotionProtocol::QUALI_TYPE_OF_COLLECT_ORDER => QualifyCollectOrder::class
        ];

        $this->setQualifyChecker(app()->make($handler[$type]));

        return $this;
    }

    protected function setQualifyChecker(Qualification $qualification)
    {
        $this->checker = $qualification;
    }

    public function check(PromotionAbleUserContract $user, $qualify_values)
    {
        return $this->checker->check($user, $qualify_values);
    }

}

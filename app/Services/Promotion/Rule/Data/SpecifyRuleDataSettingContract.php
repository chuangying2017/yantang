<?php namespace App\Services\Promotion\Rule\Data;
interface SpecifyRuleDataSettingContract {

    //关联
    public function setRelated($item_keys);

    public function getRelatedItems();

    //许可
    public function setUsable();

    public function unsetUsable();

    public function isUsable();

    //冲突
    public function unsetSameGroupUsable();

    public function setConflictUsable();

    //生效
    public function setUsing();

    public function isUsing();

    public function unsetUsing();

    //优惠内容
    public function setBenefit($value);

    public function unsetBenefit();

    //提示信息
    public function setMessage($message);

}

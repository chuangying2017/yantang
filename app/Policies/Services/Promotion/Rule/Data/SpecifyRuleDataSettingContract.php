<?php namespace App\Services\Promotion\Rule\Data;
interface SpecifyRuleDataSettingContract {

    //关联
	/**
     * @param $item_keys
     * @return $this
     */
    public function setRelated($item_keys);

    public function getRelatedItems();

    //许可
	/**
     * @return $this
     */
    public function setUsable($rule_key = null);

	/**
     * @return $this
     */
    public function unsetUsable();

    public function isUsable();

    //冲突
    public function unsetSameGroupUsable();

    public function setConflictUsable();

    public function unsetOtherUsable();

    //生效
    public function setUsing();

    public function isUsing();

    public function unsetUsing();

    //优惠内容
	/**
     * @param $value
     * @return $this
     */
    public function setBenefit($value);

	/**
     * @return $this
     */
    public function unsetBenefit();

    //提示信息
    public function setMessage($message);

    public function getMessage();

}

<?php namespace App\Services\Promotion\Rule\Data;

interface RuleDataContract extends SpecifyRuleDataContract {

    public function init($rules);

    public function sortRules();

    public function getAll($type = null);

    public function getAllKeys();

    public function getByGroup($group);

}

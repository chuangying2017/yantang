<?php namespace App\Services\Promotion\Rule\Data;

interface RuleDataContract extends SpecifyRuleDataContract {

    public function init($rules);

    public function sortRules();

    public function getAll();

    public function getAllKeys();

    public function getByGroup($group);

}

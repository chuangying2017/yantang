<?php
namespace App\Rules\ExampleRule;

use App\Rules\RuleInterface\Rule;

class SizeNumberrule implements Rule
{
    /**
     * 判断验证规则是否通过。
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return is_int($value) && $value > 0;
    }

    /**
     * 获取验证错误消息。
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is false must integer';
    }
}
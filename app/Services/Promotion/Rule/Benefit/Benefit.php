<?php namespace App\Services\Promotion\Rule\Benefit;

interface Benefit {

    public function calculate($resource, $mode, $value);

}

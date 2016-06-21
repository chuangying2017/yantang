<?php namespace App\Services\Promotion\Rule\Usage;
interface Usage {

    public function filter($items, $item_values);

}

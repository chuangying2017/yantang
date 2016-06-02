<?php namespace App\Services\Promotion\Rule\Qualification;

interface Qualification {

    public function check($user, $qualify_values);

}

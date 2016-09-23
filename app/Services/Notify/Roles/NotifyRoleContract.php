<?php namespace App\Services\Notify\Roles;


interface NotifyRoleContract {

    public static function getPhone($id);

    public static function getWeixinOpenId($id);

}

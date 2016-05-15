<?php namespace App\Http\Controllers\Backend;
trait AddUserInfo {

    public function showUserInfo()
    {
        \View::share('user_info', access()->user());
    }
}

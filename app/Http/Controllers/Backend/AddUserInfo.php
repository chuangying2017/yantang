<?php namespace App\Http\Controllers\Backend;
trait AddUserInfo {

    public function showUserInfo()
    {
        \View::share('user_info', get_current_auth_user());
    }
}

<?php namespace App\Services\Marketing;

interface MarketingInterface {

    //浏览
    public function lists($status, $user_id = null);

    //查看详情
    public function show($id);

}

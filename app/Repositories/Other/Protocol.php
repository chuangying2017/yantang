<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8/008
 * Time: 16:45
 */
namespace App\Repositories\Other;


class Protocol implements ProtocolGenerator {

    protected $fields = ['user_id','protocol_content','type','title'];

    public function createProtocol()
    {
        // TODO: Implement createProtocol() method.
    }

    public function updateProtocol($protocol_where, $protocol_data)
    {
        // TODO: Implement updateProtocol() method.
        unset($protocol_data['token']);
        return \App\models\Protocol::where($protocol_where)->update($protocol_data);
    }

    public function getAllProtocol()
    {
        // TODO: Implement getAllProtocol() method.
        return \App\models\Protocol::all($this->fields);
    }

}
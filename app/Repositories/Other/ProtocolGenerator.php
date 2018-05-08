<?php
namespace App\Repositories\Other;
Interface ProtocolGenerator{

    public function createProtocol();

    public function updateProtocol($protocol_id,$protocol_data);

    public function getAllProtocol();
}
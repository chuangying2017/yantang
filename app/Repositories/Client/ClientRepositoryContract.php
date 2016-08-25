<?php namespace App\Repositories\Client;

use App\Services\Client\ClientProtocol;

interface ClientRepositoryContract {

    public function createClient($user_id, $client_data);

    public function updateClient($user_id, $client_data);

    public function showClient($user_id, $with_user = false);

    public function deleteClient($user_id);

    public function getAllClients($status = ClientProtocol::STATUS_OK, $with_user = false, $order_by = 'created_at', $sort = 'desc');

    public function getClientsPaginated($status = ClientProtocol::STATUS_OK, $with_user = false, $order_by = 'created_at', $sort = 'desc', $per_page = ClientProtocol::PER_PAGE);

    public function block($user_id);

    public function unblock($user_id);

}

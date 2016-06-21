<?php namespace App\Repositories\Promotion;
interface PromotionRepositoryContract {

    public function create($data);

    public function getAll($not_over_time = true);

    public function getAllPaginated($not_over_time = true);

    public function get($promotion_id, $with_detail = true);

    public function update($promotion_id, $data);

    public function delete($promotion_id);

}

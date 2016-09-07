<?php namespace App\Repositories\Promotion;
interface PromotionRepositoryContract {

    public function create($data);

    public function getAll($not_over_time = true);

    public function getAllPaginated($not_over_time = true);

    public function get($promotion_id, $with_detail = true);

    public function update($promotion_id, $data);

    public function updateActiveStatus($promotion_id, $active = true);

    public function delete($promotion_id);

    public function getUsefulRules();

    public function getPromotionWithDecodeRules($promotion_id);

    public function getAllByQualifyTye($qua_type, $effect = true);

}

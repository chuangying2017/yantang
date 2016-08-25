<?php namespace App\Repositories\Product;

interface ProductIdListContract {

    public function listsOfGroup($group_id);

    public function listsOfCategory($cat_id);

    public function listsOfBrand($brand_id);

}

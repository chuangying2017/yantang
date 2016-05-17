<?php namespace App\Repositories\Search;

interface SearchableContract {

    public function search($keyword, $options = []);

}

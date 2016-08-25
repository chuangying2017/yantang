<?php namespace App\Repositories\Image;

interface ImageRepositoryContract {

    public function getAll();

    public function getAllPaginated();

    public function create($data);

    public function delete($image_ids);

    public function getToken();

}

<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminProductApiTest extends TestCase {

    /** @test */
    public function it_return_a_products_list()
    {
        $query = [
            'status' => 'up',
        ];
        $this->json('get', '/admin/products', $query, $this->getAuthHeader());

        $this->dumpResponse();
        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_all_mix_products()
    {
        $this->json('get', 'admin/products/mix-products', [], $this->getAuthHeader());

        $this->dump();
    }

    /** @test */
    public function it_can_up_or_down_a_product()
    {
        $product_id = 1;
        $this->json('put', 'admin/products/' . $product_id . '/up', [], $this->getAuthHeader());

        $this->seeInDatabase('products', ['id' => $product_id, 'status' => \App\Repositories\Product\ProductProtocol::VAR_PRODUCT_STATUS_UP]);

        $this->json('put', 'admin/products/' . $product_id . '/down', [], $this->getAuthHeader());

        $this->seeInDatabase('products', ['id' => $product_id, 'status' => \App\Repositories\Product\ProductProtocol::VAR_PRODUCT_STATUS_DOWN]);
    }

    /** @test */
    public function it_return_a_product()
    {
        $product_id = 1;
        $this->json('get', '/admin/products/' . $product_id, [], $this->getAuthHeader());

        $this->seeJsonStructure(['data' => ['images', 'skus', 'cats', 'groups', 'brand']]);
        $this->assertResponseOk();
    }

    /** @test */
    public function it_return_subscribe_products()
    {

        $this->json('get', '/stations/products', [], $this->getAuthHeader());

        $this->dumpResponse();
    }

}

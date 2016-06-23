<?php

use App\Repositories\Product\ProductRepositoryContract;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductRepoTest extends TestCase {

    use DatabaseTransactions;

    protected $productRepo;

    public function setUp()
    {
        parent::setUp();

    }

    /** @test */
    public function it_can_store_and_update_product()
    {
        $product_data = $this->getCreateInput();

        $productRepo = $this->getRepo();

        $product = $productRepo->createProduct($product_data);

        $this->seeInDatabase('product_skus', ['product_id' => $product['id'], 'type' => \App\Repositories\Product\ProductProtocol::TYPE_OF_MIX]);


//        $this->assertInstanceOf(\App\Models\Product\Product::class, $product);
//
//        $productRepo->updateProduct($product['id'], $this->getUpdateInput());
//
//        $this->assertInstanceOf(\App\Models\Product\Product::class, $product);

    }

    /** @test */
    public function it_can_create_a_subscribe_product()
    {
        $product_data = $this->getSubscribeData();

        $productRepo = $this->getRepo();

        $product = $productRepo->createProduct($product_data);

        $this->assertInstanceOf(\App\Models\Product\Product::class, $product);


    }

    /** @test */
    public function it_return_a_product()
    {
        $productRepo = $this->getRepo();
        $productRepo->getProduct(10);
    }

    /** @test */
    public function it_return_lists_of_products()
    {
        $productRepo = $this->getRepo();
        $products = $productRepo->getAllProducts();
    }


    protected function getRepo()
    {
        $app = $this->createApplication();
        return $app->make(ProductRepositoryContract::class);
    }


    private function getInput()
    {
        $json = '{"data":{"cat_id":1,"merchant_id":1,"title":"燕塘 原味酸奶饮品 200ml/盒 ","sub_title":"","digest":"简介","cover_image":"http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg","brand_id":7,"detail":"<p>详细描述</p>","type":"mix","image_ids":[1,2,3],"group_ids":[2,3],"with_invoice":0,"with_care":0,"tags":"酸奶，原味，200ml，盒装","open_time":"2016-05-01","end_time":"2016-06-01","skus":[{"name":"燕塘 原味酸奶饮品 200ml/盒","cover_image":"http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg","display_price":500,"price":400,"express_fee":0,"bar_code":"3213923298","stock":10,"income_price":350,"settle_price":380,"attr_value_ids":[3],"sku_ids":[1,2]}],"attr":[{"id":1,"name":"规格","values":[{"id":2,"name":"200ml"}]}]}}';
        return json_decode($json, true)['data'];
    }

    protected function seedAttribute()
    {
        $attr = \App\Models\Product\Attribute::create(['name' => '规格']);
        $attr->values()->createMany([
            ['id' => 1, 'name' => '100ml'],
            ['id' => 2, 'name' => '150ml'],
            ['id' => 3, 'name' => '200ml'],
            ['id' => 4, 'name' => '400ml'],
        ]);
    }

    private function getCreateInput()
    {
        return [
            "cat_id" => 1,
            "merchant_id" => 1,
            "title" => "燕塘 原味酸奶饮品 300ml/盒 ",
            "sub_title" => "",
            "digest" => "简介",
            "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg",
            "brand_id" => 7,
            "detail" => '<p><img src="http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg"></img></p>',
            "type" => "mix",
            "image_ids" => [
                1,
                2,
                3
            ],
            "group_ids" => [

            ],
            "with_invoice" => 0,
            "with_care" => 0,
            "tags" => "酸奶，原味，200ml，盒装",
            "open_time" => "2016-05-01",
            "end_time" => "2016-06-01",
            "skus" => [
                [
//                    "name" => "燕塘 原味酸奶饮品 200ml/盒",
//                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg",
                    "display_price" => 500,
                    "price" => 400,
                    "express_fee" => 0,
                    "bar_code" => "3213923298",
                    "stock" => 10,
                    "income_price" => 350,
                    "settle_price" => 380,
                    "attr_value_ids" => [

                    ],
                    "mix_skus" => [
                        ['sku_id' => 1, 'quantity' => 2],
                        ['sku_id' => 2, 'quantity' => 3]
                    ]
                ]
            ],
//            "attr" => [
//                [
//                    "id" => 1,
//                    "name" => "规格",
//                    "values" => [
//                        [
//                            "id" => 2,
//                            "name" => "200ml"
//                        ]
//                    ]
//                ]
//            ]
        ];
    }

    private function getSubscribeData()
    {
        return [
            "cat_id" => 1,
            "merchant_id" => 1,
            "title" => "燕塘 原味酸奶饮品 200ml/盒 ",
            "sub_title" => "",
            "digest" => "简介",
            "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg",
            "brand_id" => 7,
            "detail" => '<p><img src="http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg"></img></p>',
            "type" => "entity",
            "image_ids" => [
                1
            ],
            "group_ids" => [
                1
            ],
            "with_invoice" => 0,
            "with_care" => 0,
            "tags" => "酸奶，原味，200ml，盒装",
            "open_time" => "2016-05-01",
            "end_time" => "2016-06-01",
            "skus" => [
                [
                    "name" => "燕塘 原味酸奶饮品 200ml/盒",
                    "cover_image" => "http://7xp47i.com1.z0.glb.clouddn.com/pd-info-1.jpg",
                    "display_price" => 5,
                    "price" => 4,
                    "express_fee" => 0,
                    "bar_code" => "2142414",
                    "stock" => 10000,
                    "income_price" => 3.5,
                    "settle_price" => 3.80,
                    "attr_value_ids" => [

                    ]
                ]
            ]
        ];
    }
}

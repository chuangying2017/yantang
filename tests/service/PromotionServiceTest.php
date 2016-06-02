<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PromotionServiceTest extends TestCase {

    /** @test */
    public function it_return_items_data()
    {
        $items = $this->fakeItems();
        print_r($items);
    }

    protected function fakeItems()
    {
        for ($i = 1; $i < 10; $i++) {
            $items[] = $this->fakeItem();
        }
        return $items;
    }

    protected function fakeItem()
    {
        $faker = $this->getFaker();
        return [
            'id' => $faker->unique()->numberBetween(1, 20),
            'quantity' => $faker->numberBetween(1, 10),
            'price' => $faker->numberBetween(100, 10000),
            'brand' => ['id' => $faker->numberBetween(1, 10)],
            'category' => ['id' => $faker->numberBetween(1, 10)],
            'group' => ['id' => $faker->numberBetween(1, 10)],
            'discount' => 0
        ];
    }

    protected function fakeRule()
    {
        $faker = $this->getFaker();
        return [
            'id' => $faker->unique()->numberBetween(1, 999),
            'qualify' => [
                'type' => $faker->randomElement(array_keys(\App\Services\Promotion\PromotionProtocol::getQualifyType())),
                'quantity' => $faker->numberBetween(1, 10),
                'values' => $faker->randomElements(
                    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    $faker->numberBetween(1, 10)
                )
            ],
            'items' => [
                'type' => $faker->randomElement(array_keys(\App\Services\Promotion\PromotionProtocol::getItemType())),
                'values' => $faker->randomElements(
                    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    $faker->numberBetween(1, 10)
                ),
                'range' => [
                    'type' => $faker->randomElement(array_keys(\App\Services\Promotion\PromotionProtocol::getRangeType())),
                    'min' => $faker->numberBetween(1, 3),
                    'max' => $faker->numberBetween(3, 1000),
                ],
            ],
            'discount' => [
                'type' => $faker->randomElement(array_keys(\App\Services\Promotion\PromotionProtocol::getDiscountType())),
                'mode' => $faker->randomElement(array_keys(\App\Services\Promotion\PromotionProtocol::getDiscountMode())),
                'value' => $faker->numberBetween(1, 100),
            ],
            'weight' => $faker->numberBetween(0, 10),
            'multi' => $faker->numberBetween(0, 2),
        ];
    }
}

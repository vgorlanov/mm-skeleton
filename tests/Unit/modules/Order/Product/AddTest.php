<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Product;

use Tests\TestCase;
use Tests\Unit\modules\Order\OrderBuilder;

final class AddTest extends TestCase
{
    public function test_add_product_success(): void
    {
        $order = (new OrderBuilder())->build();
        $product1 = (new ProductBuilder())->build();
        $product2 = (new ProductBuilder())->build();

        $order->addProduct($product1);
        $order->addProduct($product2);

        $this->assertCount(2, $order->getProducts());
        $this->assertEquals($product1, current($order->getProducts()));
        $this->assertEquals($product2, $order->getProducts()[array_key_last($order->getProducts())]);
    }
}

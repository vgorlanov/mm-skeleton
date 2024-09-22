<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Product;

use Tests\TestCase;
use Tests\Unit\modules\Order\OrderBuilder;

final class DeleteTest extends TestCase
{
    public function test_success(): void
    {
        $product1 = (new ProductBuilder())->build();
        $product2 = (new ProductBuilder())->build();

        $order = (new OrderBuilder())->withProduct($product1)->withProduct($product2)->build();

        $this->assertCount(2, $order->getProducts());
        $order->deleteProduct($product1);
        $this->assertCount(1, $order->getProducts());
        $this->assertSame($product2, current($order->getProducts()));
    }
}

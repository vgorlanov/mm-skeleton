<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Product;

use Common\Uuid\Uuid;
use Modules\Order\Exceptions\OrderProductNotExists;
use Tests\TestCase;
use Tests\Unit\modules\Order\OrderBuilder;

final class UpdateTest extends TestCase
{
    public function test_success(): void
    {
        $product = (new ProductBuilder())->withUuid($uuid = Uuid::next())->build();
        $update = (new ProductBuilder())->withUuid($uuid)->build();

        $order = (new OrderBuilder())->withProduct($product)->build();
        $order->updateProduct($update);
        $this->assertSame($update, current($order->getProducts()));
    }

    public function test_exception(): void
    {
        $product = (new ProductBuilder())->withUuid(Uuid::next())->build();
        $order = (new OrderBuilder())->build();

        $this->expectException(OrderProductNotExists::class);
        $order->updateProduct($product);
    }
}

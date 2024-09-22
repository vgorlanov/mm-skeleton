<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Modules\Order\Domain\Price;
use Monolog\Test\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Unit\modules\Order\Product\ProductBuilder;

final class AmountTest extends TestCase
{
    #[DataProvider('pricesProvider')]
    public function test_success(int $main, int $fractional, int $quantity, int $delta, Price $result): void
    {
        $product1 = (new ProductBuilder())
            ->withPrice(new Price(main: $main, fractional: $fractional))
            ->withQuantity($quantity)
            ->build();

        $product2 = (new ProductBuilder())
            ->withPrice(new Price(main: $main / $delta, fractional: $fractional / $delta))
            ->withQuantity($quantity / $delta)
            ->build();

        $order = (new OrderBuilder())->withProduct($product1)->withProduct($product2)->build();

        $this->assertEquals($order->amount(), $result);
    }

    public static function pricesProvider(): array
    {
        return [
            ['main' => 100, 'fractional' => 50, 'quantity' => 6, 'delta' => 2, 'result' => new Price(main: 753, fractional: 75)],
        ];
    }
}

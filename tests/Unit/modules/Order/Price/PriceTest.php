<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Price;

use Modules\Order\Domain\Price;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

final class PriceTest extends TestCase
{
    #[TestWith([0, 0, false, '00.00'])]
    #[TestWith([1, 10, true, '-01.10'])]
    #[TestWith([9, 9, false, '09.09'])]
    #[TestWith([10, 10, false, '10.10'])]
    #[TestWith([999, 99, false, '999.99'])]
    #[TestWith([999, 99, true, '-999.99'])]
    public function test_create_success(int $main, int $fractional, bool $negative, string $result): void
    {
        $price = new Price(main: $main, fractional: $fractional, negative: $negative);

        $this->assertSame($price->toString(), $result);
    }

    public function test_create_exception(): void
    {
        $this->expectException(\RangeException::class);
        new Price(main: 100, fractional: 100);
    }

    #[TestWith([10.10, new Price(main: 10, fractional: 10, negative: false)])]
    public function test_make_success(float $value, Price $price): void
    {
        $this->assertEquals(Price::make($value), $price);
    }
}

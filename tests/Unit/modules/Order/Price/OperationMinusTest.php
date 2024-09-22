<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Price;

use Modules\Order\Domain\Operations\Minus;
use Modules\Order\Domain\Price;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

final class OperationMinusTest extends TestCase
{
    #[TestWith([new Price(main: 100, fractional: 0), new Price(main: 50, fractional: 50), new Price(main: 49, fractional: 50)])]
    #[TestWith([new Price(main: 100, fractional: 50), new Price(main: 100, fractional: 50), new Price(main: 0, fractional: 0)])]
    #[TestWith([new Price(main: 0, fractional: 50), new Price(main: 0, fractional: 52), new Price(main: 0, fractional: 2, negative: true)])]
    #[TestWith([new Price(main: 0, fractional: 50), new Price(main: 0, fractional: 52, negative: true), new Price(main: 1, fractional: 2)])]
    public function test_success(Price $price, Price $value, Price $result): void
    {
        $this->assertEquals((new Minus($price, $value))->calc(), $result);
    }
}

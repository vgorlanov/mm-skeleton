<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Price;

use Modules\Order\Domain\Operations\Multiply;
use Modules\Order\Domain\Price;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

final class OperationMultiplyTest extends TestCase
{
    #[TestWith([new Price(main: 1, fractional: 0), 1, new Price(main: 1, fractional: 0)])]
    #[TestWith([new Price(main: 10, fractional: 10), 100, new Price(main: 1010, fractional: 0)])]
    public function test_success(Price $price, int $multiplier, Price $result): void
    {
        $this->assertEquals((new Multiply($price, $multiplier))->calc(), $result);
    }
}

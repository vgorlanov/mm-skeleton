<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Price;

use Modules\Order\Domain\Operations\Plus;
use Modules\Order\Domain\Price;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

final class OperationPlusTest extends TestCase
{
    #[TestWith([new Price(main: 99, fractional: 99), new Price(main: 88, fractional: 88), new Price(main: 188, fractional: 87)])]
    #[TestWith([new Price(main: 0, fractional: 0), new Price(main: 0, fractional: 0), new Price(main: 0, fractional: 0)])]
    #[TestWith([new Price(main: 0, fractional: 0), new Price(main: 0, fractional: 0), new Price(main: 0, fractional: 0)])]
    #[TestWith([new Price(main: 1, fractional: 10), new Price(main: 1, fractional: 10), new Price(main: 2, fractional: 20)])]
    #[TestWith([new Price(main: 1, fractional: 10), new Price(main: 0, fractional: 10, negative: true), new Price(main: 1, fractional: 0)])]
    #[TestWith([new Price(main: 1, fractional: 10), new Price(main: 2, fractional: 20, negative: true), new Price(main: 1, fractional: 10, negative: true)])]
    public function test_success(Price $price, Price $value, Price $result): void
    {
        $this->assertEquals((new Plus($price, $value))->calc(), $result);
    }
}

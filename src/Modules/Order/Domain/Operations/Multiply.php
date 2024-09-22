<?php

declare(strict_types=1);

namespace Modules\Order\Domain\Operations;

use Modules\Order\Domain\Price;

final class Multiply implements Operation
{
    public function __construct(
        private Price $price,
        private int $multiplier,
    ) {}

    public function calc(): Price
    {
        $result = $this->price->value() * $this->multiplier;

        return Price::make($result);
    }
}

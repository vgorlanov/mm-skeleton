<?php

declare(strict_types=1);

namespace Modules\Order\Domain\Operations;

use Modules\Order\Domain\Price;

final class Plus implements Operation
{
    public function __construct(
        private Price $price,
        private Price $value,
    ) {}

    public function calc(): Price
    {
        $result = $this->price->value() + $this->value->value();

        return Price::make($result);
    }
}

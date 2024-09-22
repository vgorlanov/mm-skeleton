<?php

declare(strict_types=1);

namespace Modules\Order\Domain;

use RangeException;

/**
 * @phpstan-type PriceData array{main: non-negative-int, fractional: non-negative-int, negative: bool}
 */
final readonly class Price
{
    public int $fractional;

    public function __construct(
        public int $main,
        int $fractional = 0,
        public bool $negative = false,
    ) {
        if ($fractional > 99) {
            throw new RangeException('Fractional value must be less than 99.');
        }

        $this->fractional = $fractional;
    }

    public static function make(float $value): Price
    {
        $negative = $value < 0;

        $value = abs($value);
        $main = (int) floor($value);
        $fractional = (int)round(($value - $main) * 100);

        return new Price(
            main: $main,
            fractional: $fractional,
            negative: $negative,
        );
    }

    public function toString(string $delimiter = '.'): string
    {
        $main = match (true) {
            $this->main === 0 => '00',
            $this->main < 10 => '0' . $this->main,
            default => $this->main,
        };

        $fractional = match (true) {
            $this->fractional === 0 => '00',
            $this->fractional < 10 => '0' . $this->fractional,
            default => $this->fractional,
        };

        $result = $main . $delimiter . $fractional;

        return $this->negative ? '-' . $result : $result;
    }

    public function value(): float
    {
        $float = (float) ($this->main . '.' . $this->fractional);

        return $this->negative ? -$float : $float;
    }
}

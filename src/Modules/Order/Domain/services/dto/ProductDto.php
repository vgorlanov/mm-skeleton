<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services\dto;


use Common\Uuid\Uuid;

final readonly class ProductDto
{
    public function __construct(
        public Uuid $uuid,
        public string $name,
        public float $price,
        public int $quantity
    ) { }
}

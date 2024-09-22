<?php

declare(strict_types=1);

namespace Modules\Order\Domain;

use Common\Uuid\Uuid;
use RangeException;

/**
 * @phpstan-import-type PriceData from Price
 * @phpstan-type OrderProductData array{uuid: non-empty-string, name: non-empty-string, price: PriceData, quantity: non-negative-int}
 */
final readonly class Product
{
    /**
     * @var non-empty-string
     */
    public string $name;
    /**
     * @var non-negative-int
     */
    public int $quantity;

    /**
     * @param Uuid $uuid
     * @param non-empty-string $name
     * @param Price $price
     * @param int $quantity
     */
    public function __construct(
        public Uuid $uuid,
        string $name,
        public Price $price,
        int $quantity = 1,
    ) {
        if ($quantity < 1) {
            throw new RangeException('Quantity must be greater than 0');
        }

        //@phpstan-ignore-next-line
        if ($name === '') {
            throw new RangeException('Product name cannot be empty');
        }

        $this->quantity = $quantity;
        $this->name = $name;
    }

    /**
     * @return OrderProductData
     */
    public function toArray(): array
    {
        /** @var PriceData $price */
        $price = (array) $this->price;
        return [
            'uuid' => $this->uuid->toString(),
            'name' => $this->name,
            'price' => $price,
            'quantity' => $this->quantity,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Product;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\Order\Domain\Price;
use Modules\Order\Domain\Product;
use Random\RandomException;

final class ProductBuilder
{
    private Uuid $uuid;
    private string $name;
    private Price $price;
    private int $quantity;

    /**
     * @throws RandomException
     */
    public function __construct()
    {
        $faker = Factory::create();
        $this->uuid = Uuid::next();
        $this->name = $faker->text(random_int(10, 50));
        $this->price = new Price(
            main: random_int(0, 100),
            fractional: random_int(0, 99),
            negative: (bool) random_int(0, 1),
        );
        $this->quantity = random_int(1, 10);
    }


    public function withUuid(Uuid $uuid): self
    {
        $clone = clone $this;
        $clone->uuid = $uuid;
        return $clone;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function withPrice(Price $price): self
    {
        $clone = clone $this;
        $clone->price = $price;
        return $clone;
    }

    public function withQuantity(int $quantity): self
    {
        $clone = clone $this;
        $clone->quantity = $quantity;
        return $clone;
    }

    public function build(): Product
    {
        return new Product(
            uuid: $this->uuid,
            name: $this->name,
            price: $this->price,
            quantity: $this->quantity,
        );
    }
}

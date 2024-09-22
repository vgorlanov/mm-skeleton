<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\Order\Domain\Price;
use Modules\Order\Domain\Product;

final class OrderProductBuilder
{
    private Uuid $uuid;
    private string $name;
    private Price $price;
    private $quantity;

    public function __construct()
    {
        $faker = Factory::create();
        $this->uuid = Uuid::next();
        $this->name = $faker->words(random_int(1, 10));
        $this->price = new Price(
            main: random_int(1, 100),
            fractional: random_int(1, 100),
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

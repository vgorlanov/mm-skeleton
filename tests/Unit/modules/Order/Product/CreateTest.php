<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Product;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\Order\Domain\Price;
use Modules\Order\Domain\Product;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    public function test_create_success(): void
    {
        $faker = Factory::create();
        $price = new Price(main: random_int(0, 100), fractional: random_int(0, 99), negative: (bool) random_int(0, 1));

        $product = new Product(
            uuid: $uuid = Uuid::next(),
            name: $name = $faker->text(random_int(50, 100)),
            price: $price,
            quantity: $quantity = random_int(1, 10),
        );

        $this->assertEquals($uuid, $product->uuid);
        $this->assertEquals($name, $product->name);
        $this->assertEquals($price, $product->price);
        $this->assertEquals($quantity, $product->quantity);
    }

    public function test_create_empty_name_exception(): void
    {
        $this->expectException(\RangeException::class);
        new Product(
            uuid: Uuid::next(),
            name: '',
            price: new Price(main: 0, fractional: 0),
            quantity: 1,
        );
    }

    public function test_create_zero_quantity_exception(): void
    {
        $this->expectException(\RangeException::class);
        new Product(
            uuid: Uuid::next(),
            name: 'fake',
            price: new Price(main: 0, fractional: 0),
            quantity: 0,
        );
    }
}

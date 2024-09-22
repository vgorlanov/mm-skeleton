<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\Order\Domain\Customer;

final class CustomerBuilder
{
    private Uuid $uuid;
    private string $name;
    private string $email;

    public function __construct()
    {
        $faker = Factory::create();

        $this->uuid = Uuid::next();
        $this->name = $faker->name;
        $this->email = $faker->email;
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

    public function withEmail(string $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }

    public function build(): Customer
    {
        return new Customer(
            uuid: $this->uuid,
            name: $this->name,
            email: $this->email,
        );
    }
}

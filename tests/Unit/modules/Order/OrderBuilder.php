<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Common\Uuid\Uuid;
use DateTimeImmutable;
use Faker\Factory;
use Modules\Order\Domain\Customer;
use Modules\Order\Domain\Order;
use Modules\Order\Domain\Product;

final class OrderBuilder
{
    private Uuid $uuid;
    private Uuid $company;
    private Customer $customer;
    /**
     * @var Product[]
     */
    private array $products = [];

    private bool $cancel = false;
    private bool $active = false;
    private bool $complete = false;

    private DateTimeImmutable $date;

    public function __construct()
    {
        $faker = Factory::create();
        $this->customer = new Customer(
            uuid: Uuid::next(),
            name: $faker->name,
            email: $faker->email,
        );

        $this->uuid = Uuid::next();
        $this->company = Uuid::next();
        $this->date = new DateTimeImmutable();
    }

    public function withUuid(Uuid $uuid): self
    {
        $clone = clone $this;
        $clone->uuid = $uuid;
        return $clone;
    }

    public function withCompany(Uuid $company): self
    {
        $clone = clone $this;
        $clone->company = $company;
        return $clone;
    }

    public function withProduct(Product $product): self
    {
        $clone = clone $this;
        $clone->products[] = $product;
        return $clone;
    }

    public function complete(): self
    {
        $clone = clone $this;
        $clone->complete = true;
        return $clone;
    }

    public function activate(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function cancel(): self
    {
        $clone = clone $this;
        $clone->cancel = true;
        return $clone;
    }

    public function withCustomer(Customer $customer): self
    {
        $clone = clone $this;
        $clone->customer = $customer;
        return $clone;
    }

    public function build(): Order
    {
        $order = new Order(
            uuid: $this->uuid,
            company: $this->company,
            customer: $this->customer,
            date: $this->date,
        );

        if($this->cancel) {
            $order->cancel(new DateTimeImmutable());
        }

        if($this->complete) {
            $order->complete(new DateTimeImmutable());
        }

        if($this->active) {
            $order->activate(new DateTimeImmutable());
        }

        if($this->products) {
            foreach ($this->products as $product) {
                $order->addProduct($product);
            }
        }

        return $order;
    }
}

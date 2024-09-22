<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Repository;

use Common\Uuid\Uuid;
use Modules\Order\Exceptions\RepositoryOrderExistsException;
use Modules\Order\Exceptions\RepositoryOrderNotFoundException;
use Modules\Order\Infrastructure\Repository;
use Tests\TestCase;
use Tests\Unit\modules\Order\CustomerBuilder;
use Tests\Unit\modules\Order\OrderBuilder;
use Tests\Unit\modules\Order\Product\ProductBuilder;

abstract class Base extends TestCase
{
    abstract public function repository(): Repository;

    public function test_add_success(): void
    {
        $product1 = (new ProductBuilder())->build();
        $product2 = (new ProductBuilder())->build();
        $new = (new OrderBuilder())->withProduct($product1)->withProduct($product2)->build();

        $this->repository()->add($new);

        $order = $this->repository()->get($new->getUuid());

        $this->assertEquals($order->getUuid(), $new->getUuid());
        $this->assertEquals($order->getCompany(), $new->getCompany());
        $this->assertEquals($order->getCustomer(), $new->getCustomer());
    }

    public function test_add_exception(): void
    {
        $order = (new OrderBuilder())->build();
        $this->repository()->add($order);

        $this->expectException(RepositoryOrderExistsException::class);
        $this->repository()->add($order);
    }

    public function test_update_success(): void
    {
        $uuid = Uuid::next();
        $company = Uuid::next();
        $customer = (new CustomerBuilder())->build();

        $product = (new ProductBuilder())->build();
        $newProduct = (new ProductBuilder())->build();

        $order = (new OrderBuilder())->withUuid($uuid)->withCustomer($customer)->withCompany($company)->withProduct($product)->build();
        $update = (new OrderBuilder())->withUuid($uuid)->withCustomer($customer)->withCompany($company)->withProduct($newProduct)->build();

        $this->repository()->add($order);
        $this->repository()->update($update);

        $updated = $this->repository()->get($uuid);

        $this->assertEquals($updated->getUuid(), $order->getUuid());
        $this->assertEquals($updated->getCustomer(), $order->getCustomer());
        $this->assertEquals($updated->getCompany(), $order->getCompany());
        $this->assertNotEquals($updated->getProducts(), $order->getProducts());
    }

    public function test_update_exception(): void
    {
        $order = (new OrderBuilder())->build();
        $this->expectException(RepositoryOrderNotFoundException::class);
        $this->repository()->update($order);
    }

    public function test_remove_success(): void
    {
        $order = (new OrderBuilder())->build();
        $this->repository()->add($order);
        $this->repository()->remove($order);

        $this->expectException(RepositoryOrderNotFoundException::class);
        $this->repository()->get($order->getUuid());
    }

    public function test_remove_exception(): void
    {
        $order = (new OrderBuilder())->build();

        $this->expectException(RepositoryOrderNotFoundException::class);
        $this->repository()->remove($order);
    }
}

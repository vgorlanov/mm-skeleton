<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\Repository;

use Common\Uuid\Uuid;
use Modules\Product\Infrastructure\Repository;
use Modules\Product\Infrastructure\RepositoryProductAlreadyExistsException;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Tests\TestCase;
use Tests\Unit\modules\Product\ProductBuilder;

abstract class Base extends TestCase
{
    abstract public function repository(): Repository;

    public function test_get_exception(): void
    {
        $this->expectException(RepositoryProductNotFoundException::class);
        $this->repository()->get(Uuid::next());
    }

    public function test_add_success(): void
    {
        $new = (new ProductBuilder())->build();

        $this->repository()->add($new);

        $product = $this->repository()->get($new->getUuid());

        $this->assertEquals($product->getUuid(), $new->getUuid());
        $this->assertEquals($product->getTitle(), $new->getTitle());
        $this->assertEquals($product->getBody(), $new->getBody());
        $this->assertEquals($product->getParams(), $new->getParams());
        $this->assertEquals($product->getDate(), $new->getDate());
        $this->assertEquals($product->getImages(), $new->getImages());
    }

    public function test_add_exception(): void
    {
        $product = (new ProductBuilder())->build();

        $this->repository()->add($product);

        $this->expectException(RepositoryProductAlreadyExistsException::class);
        $this->repository()->add($product);
    }

    public function test_update_success(): void
    {
        $uuid = Uuid::next();

        $product = (new ProductBuilder())->withId($uuid)->build();
        $update = (new ProductBuilder())->withId($uuid)->build();

        $this->repository()->add($product);
        $this->repository()->update($update);

        $updated = $this->repository()->get($update->getUuid());

        $this->assertEquals($updated->getUuid(), $product->getUuid());
        $this->assertNotEquals($updated->getTitle(), $product->getTitle());
        $this->assertNotEquals($updated->getBody(), $product->getBody());
        $this->assertNotEquals($updated->getParams(), $product->getParams());
        $this->assertNotEquals($updated->getDate(), $product->getDate());
        $this->assertNotEquals($updated->getImages(), $product->getImages());
    }

    public function test_update_exception(): void
    {
        $product = (new ProductBuilder())->build();
        $this->expectException(RepositoryProductNotFoundException::class);
        $this->repository()->update($product);
    }

    public function test_remove_success(): void
    {
        $product = (new ProductBuilder())->build();
        $this->repository()->add($product);
        $this->repository()->remove($product);

        $this->expectException(RepositoryProductNotFoundException::class);
        $this->repository()->get($product->getUuid());
    }

    public function test_remove_exception(): void
    {
        $product = (new ProductBuilder())->build();

        $this->expectException(RepositoryProductNotFoundException::class);
        $this->repository()->remove($product);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Tests\Unit\modules\Product\ProductBuilder;

final class PublishTest extends ProductTest
{
    private const ROUTE = 'admin.product.publish';

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository->add($this->product);
    }

    public function test_success(): void
    {
        $this->patchJson($this->url(self::ROUTE, $this->product))->assertStatus(200);
    }

    public function test_product_not_found(): void
    {
        $product = (new ProductBuilder())->build();

        $this->patchJson($this->url(self::ROUTE, $product))->assertStatus(404);
    }

    public function test_product_already_published(): void
    {
        $product = (new ProductBuilder())->published()->build();

        $this->repository->add($product);

        $this->patchJson($this->url(self::ROUTE, $product))->assertStatus(422);
    }
}
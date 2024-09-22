<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Tests\Unit\modules\Product\ProductBuilder;

final class DeleteTest extends ProductTest
{
    private const ROUTE = 'admin.product.delete';

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository->add($this->product);
    }

    public function test_success(): void
    {
        $this->delete($this->url(self::ROUTE, $this->product))->assertStatus(200);
    }

    public function test_product_not_found(): void
    {
        $product = (new ProductBuilder())->build();

        $this->delete($this->url(self::ROUTE, $product))->assertStatus(404);
    }

    public function test_product_already_deleted(): void
    {
        $product = (new ProductBuilder())->deleted()->build();

        $this->repository->add($product);

        $this->delete($this->url(self::ROUTE, $product))->assertStatus(422);
    }
}

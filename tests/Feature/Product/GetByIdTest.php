<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Tests\Unit\modules\Product\ProductBuilder;

final class GetByIdTest extends ProductTest
{
    protected const ROUTE = 'admin.product.show';

    public function test_success(): void
    {
        $this->repository->add($this->product);

        $result = $this->get($this->url(self::ROUTE, $this->product))->json();

        $this->assertEquals($result['uuid'], $this->product->getUuid()->toString());
        $this->assertEquals($result['company'], $this->product->getCompany()->toString());
        $this->assertEquals($result['title'], $this->product->getTitle());
        $this->assertEquals($result['body'], $this->product->getBody());
        $this->assertEquals($result['params'], $this->product->getParams());
        $this->assertEquals($result['images'], $this->product->getImages());
    }

    public function test_not_found(): void
    {
        $product = (new ProductBuilder())->build();

        $this->get($this->url(self::ROUTE, $product))->assertStatus(404);
    }
}

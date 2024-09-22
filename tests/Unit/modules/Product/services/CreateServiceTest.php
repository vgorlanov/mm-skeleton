<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Domain\services\CreateService;
use Modules\Product\Domain\services\dto\ProductDto;
use Tests\Unit\modules\Product\ProductBuilder;

final class CreateServiceTest extends ProductService
{
    private ProductDto $productCreate;

    public function test_success(): void
    {
        $service = new CreateService($this->repository, $this->dispatcher);

        $product = $service->execute($this->productCreate);

        $this->assertEquals($this->repository->get($product->getUuid()), $product);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $product = (new ProductBuilder())->build();

        $this->productCreate = new ProductDto(
            company: $product->getCompany(),
            title: $product->getTitle(),
            body: $product->getBody(),
            params: $product->getParams(),
            images: $product->getImages(),
            uuid: $product->getUuid(),
        );
    }
}

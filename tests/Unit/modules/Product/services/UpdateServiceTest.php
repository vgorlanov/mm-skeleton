<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\ProductDto;
use Modules\Product\Domain\services\UpdateService;
use Tests\Unit\modules\Product\ProductBuilder;

final class UpdateServiceTest extends ProductService
{
    private Product $product;

    public function test_success(): void
    {
        $service = new UpdateService($this->repository, $this->dispatcher);

        $new = (new ProductBuilder())->build();

        $data = new ProductDto(
            company: $this->product->getCompany(),
            title: $new->getTitle(),
            body: $new->getBody(),
            params: $new->getParams(),
            images: $new->getImages(),
        );

        $service->execute($this->product->getUuid(), $data);

        $updated = $this->repository->get($this->product->getUuid());

        $this->assertTrue($updated->getTitle() === $new->getTitle() && $updated->getTitle() !== $this->product->getTitle());
        $this->assertTrue($updated->getBody() === $new->getBody() && $updated->getBody() !== $this->product->getBody());
        $this->assertTrue($updated->getParams() === $new->getParams() && $updated->getParams() !== $this->product->getParams());
        $this->assertTrue($updated->getImages() === $new->getImages() && $updated->getImages() !== $this->product->getImages());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = (new ProductBuilder())->build();

        $this->repository->add($this->product);
    }


}

<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\UnPublishDto;
use Modules\Product\Domain\services\UnPublishService;
use Tests\Unit\modules\Product\ProductBuilder;

final class UnPublishServiceTest extends ProductService
{
    private Product $product;

    public function test_success(): void
    {
        $service = new UnPublishService($this->repository, $this->dispatcher);

        $this->assertTrue($this->product->isPublished());

        $dto = new UnPublishDto($this->product->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $product = $this->repository->get($dto->uuid);

        $this->assertFalse($product->isPublished());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = (new ProductBuilder())->published()->build();

        $this->repository->add($this->product);
    }
}

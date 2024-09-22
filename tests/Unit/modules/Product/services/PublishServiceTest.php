<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\PublishDto;
use Modules\Product\Domain\services\PublishService;
use Tests\Unit\modules\Product\ProductBuilder;

final class PublishServiceTest extends ProductService
{
    private Product $product;

    public function test_success(): void
    {
        $service = new PublishService($this->repository, $this->dispatcher);

        $this->assertFalse($this->product->isPublished());

        $dto = new PublishDto($this->product->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $product = $this->repository->get($dto->uuid);
        $this->assertTrue($product->isPublished());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = (new ProductBuilder())->build();

        $this->repository->add($this->product);
    }

}

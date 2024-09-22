<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\dto\RestoreDto;
use Modules\Product\Domain\services\RestoreService;
use Tests\Unit\modules\Product\ProductBuilder;

final class RestoreServiceTest extends ProductService
{
    private Product $product;

    public function test_success(): void
    {
        $service = new RestoreService($this->repository, $this->dispatcher);

        $this->assertInstanceOf(\DateTimeInterface::class, $this->product->isDeleted());

        $dto = new RestoreDto($this->product->getUuid());
        $service->execute($dto);

        $product = $this->repository->get($dto->uuid);

        $this->assertFalse($product->isDeleted());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = (new ProductBuilder())->deleted()->build();

        $this->repository->add($this->product);
    }

}

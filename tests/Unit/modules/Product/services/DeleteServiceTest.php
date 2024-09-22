<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Domain\Product;
use Modules\Product\Domain\services\DeleteService;
use Modules\Product\Domain\services\dto\DeleteDto;
use Tests\Unit\modules\Product\ProductBuilder;

final class DeleteServiceTest extends ProductService
{
    private Product $product;

    public function test_success(): void
    {
        $service = new DeleteService($this->repository, $this->dispatcher);

        $this->assertFalse($this->product->isDeleted());

        $dto = new DeleteDto($this->product->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $product = $this->repository->get($dto->uuid);

        $this->assertInstanceOf(\DateTimeInterface::class, $product->isDeleted());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = (new ProductBuilder())->build();

        $this->repository->add($this->product);
    }

}

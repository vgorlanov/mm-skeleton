<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Common\Uuid\Uuid;
use Modules\Company\Domain\Company;
use Modules\Product\Domain\Product;
use Modules\Product\Infrastructure\MemoryRepository;
use Modules\Product\Infrastructure\Repository;
use Tests\TestCase;
use Tests\Unit\modules\Company\CompanyBuilder;
use Tests\Unit\modules\Product\ProductBuilder;

abstract class ProductTest extends TestCase
{
    protected Repository $repository;

    protected Product $product;

    protected Company $company;

    protected array $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = (new ProductBuilder())->build();

        $this->app->singleton(Repository::class, MemoryRepository::class);

        $this->repository = app()->make(Repository::class);

        $this->request = [
            'company' => Uuid::next()->toString(),
            'title'   => $this->product->getTitle(),
            'body'    => $this->product->getBody(),
            'params'  => $this->product->getParams(),
            'images'  => $this->product->getImages(),
        ];

        $mock = $this->createMock(\Modules\Company\Infrastructure\Repository::class);
        $mock
            ->expects($this->any())
            ->method('get')
            ->willReturn((new CompanyBuilder())->build());

        $this->app->singleton(\Modules\Company\Infrastructure\Repository::class, fn() => $mock);
    }
}

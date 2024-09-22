<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\services;

use Modules\Product\Infrastructure\MemoryRepository;
use Modules\Product\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tests\TestCase;

abstract class ProductService extends TestCase
{
    protected Repository $repository;

    protected EventDispatcherInterface $dispatcher;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);
        $this->repository = app()->make(Repository::class);

        $this->app->singleton(EventDispatcherInterface::class, fn() => $this->createStub(EventDispatcherInterface::class));

        $this->dispatcher = app()->make(EventDispatcherInterface::class);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Infrastructure\MemoryRepository;
use Modules\Company\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tests\TestCase;

abstract class CompanyService extends TestCase
{
    protected Repository $repository;

    protected EventDispatcherInterface $dispatcher;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);
        $this->repository = app()->make(Repository::class);

        $this->dispatcher = app()->make(EventDispatcherInterface::class);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\services;

use Modules\User\Domain\User;
use Modules\User\Infrastructure\MemoryRepository;
use Modules\User\Infrastructure\Repository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tests\TestCase;
use Tests\Unit\modules\User\UserBuilder;

abstract class UserService extends TestCase
{
    protected Repository $repository;

    protected EventDispatcherInterface $dispatcher;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);
        $this->repository = app()->make(Repository::class);

        $this->dispatcher = app()->make(EventDispatcherInterface::class);

        $this->user = (new UserBuilder())->build();

        $this->repository->add($this->user);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Modules\User\Domain\User;
use Modules\User\Infrastructure\MemoryRepository;
use Modules\User\Infrastructure\Repository;
use Tests\TestCase;

abstract class UserTest extends TestCase
{
    protected Repository $repository;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);

        $this->repository = app()->make(Repository::class);
    }

}

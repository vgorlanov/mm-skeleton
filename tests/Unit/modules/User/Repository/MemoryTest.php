<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\Repository;

use Modules\User\Infrastructure\MemoryRepository;
use Modules\User\Infrastructure\Repository;

final class MemoryTest extends Base
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);
    }

    public function repository(): Repository
    {
        return app()->make(Repository::class);
    }
}

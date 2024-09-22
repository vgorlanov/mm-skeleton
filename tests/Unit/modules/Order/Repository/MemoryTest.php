<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Repository;

use Modules\Order\Infrastructure\MemoryRepository;
use Modules\Order\Infrastructure\Repository;

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

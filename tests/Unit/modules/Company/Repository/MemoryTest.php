<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\Repository;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Infrastructure\MemoryRepository;
use Modules\Company\Infrastructure\Repository;

final class MemoryTest extends Base
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, MemoryRepository::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function repository(): Repository
    {
        return app()->make(Repository::class);
    }
}

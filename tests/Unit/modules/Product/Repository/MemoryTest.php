<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\Repository;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Product\Infrastructure\MemoryRepository;
use Modules\Product\Infrastructure\Repository;

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

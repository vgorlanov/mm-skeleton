<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\Repository;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Company\Infrastructure\DBRepository;
use Modules\Company\Infrastructure\Repository;

final class DBTest extends Base
{
    use DatabaseTransactions;

    /**
     * @throws BindingResolutionException
     */
    public function repository(): Repository
    {
        return app()->make(Repository::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, DBRepository::class);
    }
}

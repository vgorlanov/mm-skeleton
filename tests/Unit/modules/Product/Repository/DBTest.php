<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Product\Repository;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Product\Infrastructure\DBRepository;
use Modules\Product\Infrastructure\Repository;

final class DBTest extends Base
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, DBRepository::class);
    }


    /**
     * @return Repository
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function repository(): Repository
    {
        return app()->make(Repository::class);
    }
}

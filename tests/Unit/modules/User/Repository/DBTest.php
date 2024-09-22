<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User\Repository;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Infrastructure\DBRepository;
use Modules\User\Infrastructure\Repository;

final class DBTest extends Base
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(Repository::class, DBRepository::class);
    }

    public function repository(): Repository
    {
        return app()->make(Repository::class);
    }
}

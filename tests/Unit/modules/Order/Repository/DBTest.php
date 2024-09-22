<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order\Repository;

use Modules\Order\Infrastructure\DBRepository;
use Modules\Order\Infrastructure\Repository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        return app()->make(DBRepository::class);
    }
}

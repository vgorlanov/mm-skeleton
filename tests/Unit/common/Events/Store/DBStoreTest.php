<?php

declare(strict_types=1);

namespace Tests\Unit\common\Events\Store;

use Common\Events\EventStore\DBEventStore;
use Common\Events\EventStore\EventStore;
use Illuminate\Foundation\Testing\DatabaseTransactions;

final class DBStoreTest extends StoreTest
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(EventStore::class, DBEventStore::class);
    }

    public function store(): EventStore
    {
        return $this->app->make(EventStore::class);
    }
}

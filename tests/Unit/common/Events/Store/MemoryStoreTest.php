<?php

declare(strict_types=1);

namespace Tests\Unit\common\Events\Store;

use Common\Events\EventStore\EventStore;
use Common\Events\EventStore\MemoryEventStore;

final class MemoryStoreTest extends StoreTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(EventStore::class, MemoryEventStore::class);
    }

    public function store(): EventStore
    {
        return $this->app->make(EventStore::class);
    }
}

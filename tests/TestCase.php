<?php

namespace Tests;

use Common\EntityAggregate;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(EventDispatcherInterface::class, fn() => $this->createStub(EventDispatcherInterface::class));
    }

    /**
     * @param string $name
     * @param EntityAggregate|null $entity
     * @return string
     */
    protected function url(string $name, ?EntityAggregate $entity = null): string
    {
        return $entity !== null ? route($name, ['uuid' => $entity->getUuid()->toString()]) : route($name);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\common\Events;

use Common\Events\Attributes\Listener;
use Common\Events\DomainEvent;
use Common\Events\EventDispatcher;
use Common\Events\Exceptions\InvalidListenerException;
use Common\Events\ListenerProvider;
use DateTimeImmutable;
use Exception;
use Tests\TestCase;
use Tests\Unit\Asset\TestListenerException;
use Tests\Unit\Asset\TestListenerSuccess;

#[Listener(TestListenerSuccess::class)]
final class TestDomainEventSuccess extends DomainEvent
{
    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function toJson(): string
    {
        return json_encode('json', JSON_THROW_ON_ERROR);
    }
}

#[Listener(TestListenerException::class)]
final class TestDomainEventException extends DomainEvent
{
    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function toJson(): string
    {
        return json_encode('json', JSON_THROW_ON_ERROR);
    }
}

final class ListenerProviderTest extends TestCase
{
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = new EventDispatcher(listenerProvider: new ListenerProvider());
    }

    /**
     * @throws Exception
     */
    public function test_listener_success(): void
    {
        $event = new TestDomainEventSuccess();
        $dispatchEvent = $this->dispatcher->dispatch($event);
        $this->assertEquals($event, $dispatchEvent);
        $this->assertEquals($event->getEventId(), $dispatchEvent->getEventId());
    }

    /**
     * @throws Exception
     */
    public function test_listener_exception(): void
    {
        $this->expectException(InvalidListenerException::class);
        $this->dispatcher->dispatch(new TestDomainEventException());
    }
}

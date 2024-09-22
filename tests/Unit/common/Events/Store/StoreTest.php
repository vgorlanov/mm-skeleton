<?php

declare(strict_types=1);

namespace Tests\Unit\common\Events\Store;

use Common\Events\DomainEvent;
use Common\Events\EventStore\EventStore;
use Common\Events\Exceptions\InvalidEventStoreException;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

abstract class StoreTest extends TestCase
{
    abstract public function store(): EventStore;

    /**
     * @throws Exception
     */
    public function test_append_success(): void
    {
        $event = $this->makeEvent($date = new DateTimeImmutable());
        $this->store()->append($event);

        $stored = current($this->store()->allEventsSince($event->getEventId()));

        $this->assertEquals($stored->event, $event);
        $this->assertEquals($stored->occurredOn, $date);
    }

    /**
     * @throws Exception
     */
    public function test_append_exists_exception(): void
    {
        $event = $this->makeEvent(new DateTimeImmutable());
        $this->store()->append($event);

        $this->expectException(InvalidEventStoreException::class);
        $this->store()->append($event);
    }

    /**
     * @throws Exception
     */
    public function test_since_success(): void
    {
        $event1 = $this->makeEvent();
        $event2 = $this->makeEvent();
        $event3 = $this->makeEvent();

        $this->store()->append($event1);
        $this->store()->append($event2);
        $this->store()->append($event3);

        $storedEvents = $this->store()->allEventsSince($event1->getEventId());
        $stored = current(array_slice($storedEvents, 1, 1));

        $since = $this->store()->allEventsSince($stored->eventId);

        $this->assertCount(2, $since);
    }

    /**
     * @throws Exception
     */
    public function test_delete_success(): void
    {
        $event = $this->makeEvent();
        $this->store()->append($event);

        $stored = $this->store()->allEventsSince($event->getEventId());

        $this->assertCount(1, $stored);

        $this->store()->delete($event);

        $this->expectException(InvalidEventStoreException::class);
        $this->store()->allEventsSince($event->getEventId());
    }

    /**
     * @throws Exception
     */
    protected function makeEvent(DateTimeImmutable $date = null): DomainEvent
    {
        $date = $date ?: new DateTimeImmutable();

        $event = $this->createStub(DomainEvent::class);
        $event->method('occurredOn')->willReturn($date);
        $event->method('getEventId')->willReturn(Uuid::next());

        return $event;
    }
}

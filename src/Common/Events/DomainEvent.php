<?php

declare(strict_types=1);

namespace Common\Events;

use Common\Events\EventStore\PersistEvent;
use Common\Uuid\Uuid;
use DateTimeImmutable;

abstract class DomainEvent implements PersistEvent
{
    private Uuid $eventId;

    public function __construct()
    {
        $this->eventId = Uuid::next();
    }

    /**
     * @return DateTimeImmutable
     */
    abstract public function occurredOn(): DateTimeImmutable;

    /**
     * @throws \JsonException
     */
    abstract public function toJson(): string;

    public function getEventId(): Uuid
    {
        return $this->eventId;
    }
}

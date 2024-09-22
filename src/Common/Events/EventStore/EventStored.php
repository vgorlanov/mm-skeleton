<?php

declare(strict_types=1);

namespace Common\Events\EventStore;

use Common\Uuid\Uuid;
use DateTimeImmutable;

final readonly class EventStored
{
    public function __construct(
        public Uuid $eventId,
        public string $eventName,
        public DateTimeImmutable $occurredOn,
        public PersistEvent $event,
    ) {}
}

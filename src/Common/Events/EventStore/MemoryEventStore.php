<?php

declare(strict_types=1);

namespace Common\Events\EventStore;

use Common\Events\Exceptions\InvalidEventStoreException;
use Common\Uuid\Uuid;

final class MemoryEventStore implements EventStore
{
    /**
     * @var array <string, EventStored>
     */
    private array $store = [];

    public function append(PersistEvent $event): void
    {
        if (isset($this->store[$event->getEventId()->toString()])) {
            throw new InvalidEventStoreException(
                sprintf(
                    'Событие "%s" уже существует',
                    $event->getEventId()->toString(),
                ),
            );
        }

        $stored = new EventStored(
            eventId: $event->getEventId(),
            eventName: $event::class,
            occurredOn: $event->occurredOn(),
            event: clone $event,
        );

        $this->store[$event->getEventId()->toString()] = $stored;
    }

    public function delete(PersistEvent $event): void
    {
        unset($this->store[$event->getEventId()->toString()]);
    }

    public function allEventsSince(Uuid $eventId = null): array
    {
        if ($eventId !== null && !isset($this->store[$eventId->toString()])) {
            throw new InvalidEventStoreException('Событие ' . $eventId->toString() . ' не найдено');
        }

        return $eventId !== null ? $this->getEventsSince($eventId) : $this->store;
    }

    /**
     * @param Uuid $eventId
     * @return array<EventStored>
     */
    private function getEventsSince(Uuid $eventId): array
    {
        $event = $this->store[$eventId->toString()];
        return array_filter($this->store, fn($item) => $item->occurredOn >= $event->occurredOn);
    }
}

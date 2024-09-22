<?php

declare(strict_types=1);

namespace Common\Events\EventStore;

use Common\Events\Exceptions\InvalidEventStoreException;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class DBEventStore implements EventStore
{
    private const string TABLE = 'events_store';

    public function append(PersistEvent $event): void
    {
        if ($this->exists($event->getEventId())) {
            throw new InvalidEventStoreException(
                sprintf(
                    'Событие "%s" уже существует',
                    $event->getEventId()->toString(),
                ),
            );
        }

        $this->builder()->insert([
            'event_id'    => $event->getEventId()->toString(),
            'event_name'  => $event::class,
            'occurred_on' => $event->occurredOn()->format('Y-m-d H:i:s.u'),
            'event'       => base64_encode(serialize($event)),
        ]);
    }

    public function allEventsSince(Uuid $eventId = null): array
    {
        if ($eventId !== null && !$this->exists($eventId)) {
            throw new InvalidEventStoreException('Событие ' . $eventId->toString() . ' не найдено');
        }

        $builder = $this->builder();

        if ($eventId !== null) {
            $builder->where('event_id', '>=', $eventId->toString());
        }

        return $builder->get()
            ->keyBy('event_id')
            ->map(fn($item) => new EventStored(
                eventId: new Uuid($item->event_id),
                eventName: $item->event_name,
                occurredOn: new DateTimeImmutable($item->occurred_on),
                event: unserialize(base64_decode($item->event, true)),
            ))
            ->toArray();
    }

    public function delete(PersistEvent $event): void
    {
        $this->builder()->where('event_id', $event->getEventId()->toString())->delete();
    }

    private function builder(): Builder
    {
        return DB::table(self::TABLE);
    }

    private function exists(Uuid $eventId): bool
    {
        $exists = $this->builder()->where('event_id', $eventId->toString())->get();

        return (bool) $exists->count();
    }
}

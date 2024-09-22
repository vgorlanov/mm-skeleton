<?php

declare(strict_types=1);

namespace Common\Events\EventStore;

use Common\Uuid\Uuid;

interface EventStore
{
    /**
     * @param PersistEvent $event
     * @return void
     */
    public function append(PersistEvent $event): void;

    /**
     * @param PersistEvent $event
     * @return void
     */
    public function delete(PersistEvent $event): void;

    /**
     * @param Uuid|null $eventId
     * @return array<string, EventStored>
     */
    public function allEventsSince(Uuid $eventId = null): array;
}

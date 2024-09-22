<?php

declare(strict_types=1);

namespace Common\Events\EventStore;

use Common\Uuid\Uuid;
use DateTimeImmutable;

interface PersistEvent
{
    public function occurredOn(): DateTimeImmutable;

    public function toJson(): string;

    public function getEventId(): Uuid;
}

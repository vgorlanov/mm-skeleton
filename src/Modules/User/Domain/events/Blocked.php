<?php

declare(strict_types=1);

namespace Modules\User\Domain\events;

use Common\Events\Attributes\Listener;
use Common\Events\DomainEvent;
use Common\Events\Listeners\PersistDomainEventListener;
use Common\Uuid\Uuid;
use DateTimeImmutable;

#[Listener(PersistDomainEventListener::class)]
final class Blocked extends DomainEvent
{
    private DateTimeImmutable $occurred;

    public function __construct(
        private readonly Uuid $uuid,
    ) {
        parent::__construct();

        $this->occurred = new DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurred;
    }

    public function toJson(): string
    {
        return json_encode([
            'uuid'       => $this->uuid->toString(),
            'occurredOn' => $this->occurred,
        ], JSON_THROW_ON_ERROR);
    }
}

<?php

declare(strict_types=1);

namespace Modules\User\Domain\events;

use Common\Events\Attributes\Listener;
use Common\Events\DomainEvent;
use Common\Events\Listeners\PersistDomainEventListener;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\User\Domain\Data;

#[Listener(PersistDomainEventListener::class)]
final class DataChanged extends DomainEvent
{
    private DateTimeImmutable $occurred;

    public function __construct(
        private readonly Uuid $uuid,
        private readonly Data $data,
    ) {
        parent::__construct();

        $this->occurred = new DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurred;
    }

    /**
     * @throws \JsonException
     */
    public function toJson(): string
    {
        return json_encode([
            'uuid'       => $this->uuid->toString(),
            'occurredOn' => $this->occurred,
            'data'       => $this->data,
        ], JSON_THROW_ON_ERROR);
    }
}

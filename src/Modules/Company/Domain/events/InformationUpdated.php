<?php

declare(strict_types=1);

namespace Modules\Company\Domain\events;

use Common\Events\Attributes\Listener;
use Common\Events\DomainEvent;
use Common\Events\Listeners\PersistDomainEventListener;
use Common\Events\Listeners\QueueEventListener;
use Common\Queue\Queueable;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\Company\Domain\Information;

#[Listener(PersistDomainEventListener::class)]
#[Listener(QueueEventListener::class)]
final class InformationUpdated extends DomainEvent implements Queueable
{
    private DateTimeImmutable $occurred;

    public function __construct(
        private readonly Uuid $uuid,
        private readonly Information $information,
    ) {
        parent::__construct();

        $this->occurred = new DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getInformation(): Information
    {
        return $this->information;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurred;
    }

    public function toJson(): string
    {
        return json_encode([
            'uuid'        => $this->uuid->toString(),
            'occurredOn'  => $this->occurred,
            'information' => $this->information,
        ], JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            'uuid'        => $this->uuid->toString(),
            'occurredOn'  => $this->occurred,
            'information' => $this->information,
        ];
    }
}

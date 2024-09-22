<?php

declare(strict_types=1);

namespace Modules\Order\Domain\events;

use Common\Events\DomainEvent;
use Common\Queue\Queueable;
use Common\Uuid\Uuid;
use DateTimeImmutable;

final class Cancel extends DomainEvent implements Queueable
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

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'occurredOn' => $this->occurred,
        ];
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurred;
    }
}

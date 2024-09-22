<?php

declare(strict_types=1);

namespace Modules\Company\Domain\events;

use Common\Events\DomainEvent;
use Common\Uuid\Uuid;
use DateTimeImmutable;
use Modules\Company\Domain\Contacts;

final class ContactsUpdated extends DomainEvent
{
    private DateTimeImmutable $occurred;

    public function __construct(
        private readonly Uuid $uuid,
        private readonly Contacts $contacts,
    ) {
        parent::__construct();

        $this->occurred = new DateTimeImmutable();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getContacts(): Contacts
    {
        return $this->contacts;
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
            'contacts'   => $this->contacts,
        ], JSON_THROW_ON_ERROR);
    }
}

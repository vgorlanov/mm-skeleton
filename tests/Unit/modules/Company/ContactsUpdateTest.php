<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\ContactsUpdated;
use Tests\TestCase;

final class ContactsUpdateTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $new = (new CompanyBuilder())->build();

        $company->changeContacts(clone $new->getContacts());

        $this->assertEquals($company->getContacts(), $new->getContacts());
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var ContactsUpdated $event */
        $event = end($events);
        $this->assertInstanceOf(ContactsUpdated::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($company->getUuid(), $event->getUuid());
        $this->assertSame($company->getContacts(), $event->getContacts());

        $json = json_encode([
            'uuid'       => $company->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
            'contacts'   => $company->getContacts(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}

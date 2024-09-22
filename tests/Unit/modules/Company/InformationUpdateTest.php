<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\InformationUpdated;
use Tests\TestCase;

final class InformationUpdateTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $new = (new CompanyBuilder())->build();

        $company->changeInformation(clone $new->getInformation());

        $this->assertEquals($company->getInformation(), $new->getInformation());
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var InformationUpdated $event */
        $event = end($events);
        $this->assertInstanceOf(InformationUpdated::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($company->getUuid(), $event->getUuid());
        $this->assertSame($company->getInformation(), $event->getInformation());

        $json = json_encode([
            'uuid'        => $company->getUuid()->toString(),
            'occurredOn'  => $event->occurredOn(),
            'information' => $company->getInformation(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}

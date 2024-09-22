<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\AboutUpdated;
use Tests\TestCase;

final class AboutUpdateTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $new = (new CompanyBuilder())->build();

        $company->changeAbout(clone $new->getAbout());

        $this->assertEquals($company->getAbout(), $new->getAbout());
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var AboutUpdated $event */
        $event = end($events);
        $this->assertInstanceOf(AboutUpdated::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($company->getUuid(), $event->getUuid());
        $this->assertSame($company->getAbout(), $event->getAbout());

        $json = json_encode([
            'uuid'       => $company->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
            'about'      => $company->getAbout(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}

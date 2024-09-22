<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use DateTimeImmutable;
use Modules\Company\Domain\events\Activated;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Tests\TestCase;

final class ActivateTest extends TestCase
{
    /**
     * @return void
     * @throws CompanyAlreadyActivatedException
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();
        $company->activate(new DateTimeImmutable());

        $this->assertSame($company->status()->current(), Status::ACTIVE);
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var Activated $event */
        $event = end($events);
        $this->assertInstanceOf(Activated::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($company->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid'       => $company->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    /**
     * @return void
     * @throws CompanyAlreadyActivatedException
     */
    public function test_exception(): void
    {
        $company = (new CompanyBuilder())->activated()->build();

        $this->expectException(CompanyAlreadyActivatedException::class);
        $company->activate(new DateTimeImmutable());
    }

}

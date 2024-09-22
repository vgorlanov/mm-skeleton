<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\UnBlocked;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyBlockedException;
use Tests\TestCase;

final class UnblockTest extends TestCase
{
    /**
     * @return void
     * @throws CompanyBlockedException
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->blocked()->build();

        $company->unblock(new \DateTimeImmutable());

        $this->assertSame($company->status()->current(), Status::NEW);
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var UnBlocked $event */
        $event = end($events);
        $this->assertInstanceOf(UnBlocked::class, $event);
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
     * @throws CompanyBlockedException
     */
    public function test_exception(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->expectException(CompanyBlockedException::class);
        $company->unblock(new \DateTimeImmutable());
    }
}

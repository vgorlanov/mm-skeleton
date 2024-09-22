<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\Blocked;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyBlockedException;
use Tests\TestCase;

final class BlockTest extends TestCase
{
    /**
     * @throws CompanyBlockedException
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $company->block(new \DateTimeImmutable());

        $this->assertSame($company->status()->current(), Status::BLOCK);
        $this->assertNotEmpty($events = $company->events()->release());


        /** @var Blocked $event */
        $event = end($events);
        $this->assertInstanceOf(Blocked::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertEquals($company->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid'       => $company->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_exception(): void
    {
        $company = (new CompanyBuilder())->blocked()->build();

        $this->expectException(CompanyBlockedException::class);
        $company->block(new \DateTimeImmutable());
    }
}

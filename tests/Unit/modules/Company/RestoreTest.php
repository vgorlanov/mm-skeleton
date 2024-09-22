<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\Restored;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyDeletedException;
use Tests\TestCase;

final class RestoreTest extends TestCase
{
    /**
     * @return void
     * @throws CompanyDeletedException
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->deleted()->build();

        $company->restore(new \DateTimeImmutable());

        $this->assertSame($company->status()->current(), Status::NEW);
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var Restored $event */
        $event = end($events);
        $this->assertInstanceOf(Restored::class, $event);
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
     * @throws CompanyDeletedException
     */
    public function test_exception(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->expectException(CompanyDeletedException::class);
        $company->restore(new \DateTimeImmutable());
    }
}

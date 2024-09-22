<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Modules\Company\Domain\events\Deleted;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyDeletedException;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @return void
     * @throws CompanyDeletedException
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $company->delete(new \DateTimeImmutable());

        $this->assertSame($company->status()->current(), Status::DELETE);
        $this->assertNotEmpty($events = $company->events()->release());

        /** @var Deleted $event */
        $event = end($events);
        $this->assertInstanceOf(Deleted::class, $event);
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
        $company = (new CompanyBuilder())->deleted()->build();

        $this->expectException(CompanyDeletedException::class);
        $company->delete(new \DateTimeImmutable());
    }
}

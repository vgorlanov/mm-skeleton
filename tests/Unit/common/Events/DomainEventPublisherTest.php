<?php

declare(strict_types=1);

namespace Tests\Unit\common\Events;

use BadMethodCallException;
use Common\Events\DomainEvent;
use Common\Events\DomainEventPublisher;
use Common\Status\Exceptions\CurrentStatusNotExistsException;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;
use Tests\Unit\modules\Company\CompanyBuilder;

final class DomainEventPublisherTest extends TestCase
{
    private DomainEventPublisher $publisher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publisher = DomainEventPublisher::instance();
    }

    /**
     * @throws CurrentStatusNotExistsException
     * @throws Exception
     */
    public function test_publish_success(): void
    {
        $company = (new CompanyBuilder())->build();
        $company2 = (new CompanyBuilder())->build();

        $company->events()->release();
        $company2->events()->release();

        $event = $this->createStub(DomainEvent::class);
        $event2 = $this->createStub(DomainEvent::class);

        $company->events()->publish($event);
        $company2->events()->publish($event2);

        $release = $company->events()->release();
        $this->assertCount(1, $release);
        $this->assertEquals($event, current($release));

        $release2 = $company2->events()->release();
        $this->assertCount(1, $release2);
        $this->assertEquals($event2, current($release2));
    }

    /**
     * @throws CurrentStatusNotExistsException
     * @throws Exception
     */
    public function test_several_publish_success(): void
    {
        $company = (new CompanyBuilder())->build();
        $company->events()->release();

        $event = $this->createStub(DomainEvent::class);
        $event2 = $this->createStub(DomainEvent::class);

        $company->events()->publish($event);
        $company->events()->publish($event2);

        $releases = $company->events()->release();
        $this->assertCount(2, $releases);
        $this->assertEquals(current($releases), $event);
        $this->assertEquals(end($releases), $event);
    }

    /**
     * @throws CurrentStatusNotExistsException
     */
    public function test_release_empty(): void
    {
        $company = (new CompanyBuilder())->build();
        $company->events()->release();

        $this->assertCount(0, $company->events()->release());
        $this->assertIsArray($company->events()->release());
    }

    public function test_clone_exception(): void
    {
        $this->expectException(BadMethodCallException::class);
        clone $this->publisher;
    }
}

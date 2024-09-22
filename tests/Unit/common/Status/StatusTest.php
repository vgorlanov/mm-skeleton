<?php

declare(strict_types=1);

namespace Tests\Unit\common\Status;

use Common\Status\Exceptions\CurrentStatusNotExistsException;
use Common\Status\Status;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * @phpstan-import-type CollectionStatuses from \Common\Status\Status
 */
final class StatusTest extends TestCase
{
    /**
     * @var array<CollectionStatuses>
     */
    private array $statuses = [];

    private Status $status;

    protected function setUp(): void
    {
        parent::setUp();

        $this->statuses[] = [
            'date'   => new DateTimeImmutable(),
            'status' => Statuses::EXISTS,
        ];

        $this->status = new Status($this->statuses);
    }

    public function test_add_success(): void
    {
        $statuses = $this->status->add(Statuses::NOT, new DateTimeImmutable());

        $this->assertCount(2, $statuses);
        $this->assertEquals(Statuses::NOT, $statuses[count($statuses) - 1]['status']);
    }

    public function test_title_success(): void
    {
        $this->status->add(Statuses::EXISTS, new DateTimeImmutable());

        $this->assertIsString($this->status->title());
    }

    /**
     * @throws CurrentStatusNotExistsException
     */
    public function test_current_success(): void
    {
        $this->status->add(Statuses::EXISTS, new DateTimeImmutable());

        $current = $this->status->current();

        $this->assertEquals(Statuses::EXISTS, $current);
    }

    public function test_current_exception(): void
    {
        $statuses = [];
        $status = new Status($statuses);
        $this->expectException(CurrentStatusNotExistsException::class);
        $status->current();
    }

    public function test_list_success(): void
    {
        $statuses = $this->status->add(Statuses::NOT, new DateTimeImmutable());

        $this->assertSame($statuses, $this->status->list());
    }

}

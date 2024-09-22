<?php

declare(strict_types=1);

namespace Common\Status;

use Common\Title\HasTitle;
use Common\Status\Exceptions\CurrentStatusNotExistsException;
use DateTimeImmutable;

/**
 * @phpstan-type CollectionStatuses array{date: DateTimeImmutable, status: HasTitle}
 */
final class Status
{
    /**
     * @param array<CollectionStatuses> $statuses
     */
    public function __construct(
        private array &$statuses,
    ) {}

    /**
     * @param HasTitle $status
     * @param DateTimeImmutable $date
     * @return array<CollectionStatuses>
     */
    public function add(HasTitle $status, DateTimeImmutable $date): array
    {
        $this->statuses[] = [
            'date'   => $date,
            'status' => $status,
        ];

        return $this->statuses;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->current()->title();
    }

    /**
     * @return HasTitle
     */
    public function current(): HasTitle
    {
        if (count($this->statuses)) {
            return $this->statuses[count($this->statuses) - 1]['status'];
        }

        throw new CurrentStatusNotExistsException('Текущий статус не установлен');
    }

    /**
     * @return array<CollectionStatuses>
     */
    public function list(): array
    {
        return $this->statuses;
    }
}

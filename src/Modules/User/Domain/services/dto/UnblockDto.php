<?php

declare(strict_types=1);

namespace Modules\User\Domain\services\dto;

use Common\Uuid\Uuid;

final readonly class UnblockDto
{
    public function __construct(
        public Uuid $uuid,
        public \DateTimeImmutable $date,
    ) {}
}
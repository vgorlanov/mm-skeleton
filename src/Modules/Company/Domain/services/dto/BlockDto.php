<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services\dto;

use Common\Uuid\Uuid;

final class BlockDto
{
    public function __construct(
        public Uuid $uuid,
        public \DateTimeImmutable $date,
    ) {}
}
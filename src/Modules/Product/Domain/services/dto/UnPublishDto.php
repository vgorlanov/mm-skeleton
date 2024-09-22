<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services\dto;

use Common\Uuid\Uuid;

final readonly class UnPublishDto
{
    public function __construct(
        public Uuid $uuid,
        public \DateTimeImmutable $date,
    ) {}
}

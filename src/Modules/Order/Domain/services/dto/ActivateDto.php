<?php

declare(strict_types=1);


namespace Modules\Order\Domain\services\dto;


use Common\Uuid\Uuid;

final readonly class ActivateDto
{
    public function __construct(
        public Uuid $uuid,
        public \DateTimeImmutable $date
    ) {}

}
